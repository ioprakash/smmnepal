#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "${BASH_SOURCE[0]}")/.."

have_cmd() { command -v "$1" >/dev/null 2>&1; }

random_hex() {
  python3 - <<'PY'
import secrets
print(secrets.token_hex(16))
PY
}

random_key64() {
  python3 - <<'PY'
import secrets
print(secrets.token_hex(32))
PY
}

random_safe_password() {
  # Avoid $ to prevent compose interpolation issues
  python3 - <<'PY'
import secrets
alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_=+.,'
print(''.join(secrets.choice(alphabet) for _ in range(24)))
PY
}

ensure_ubuntu_packages() {
  if [[ ! -r /etc/os-release ]]; then
    return 0
  fi
  . /etc/os-release
  if [[ "${ID:-}" != "ubuntu" && "${ID_LIKE:-}" != *debian* ]]; then
    return 0
  fi

  sudo apt-get update -y
  sudo apt-get install -y docker.io docker-compose-v2
  sudo systemctl enable --now docker || true
}

if ! have_cmd python3; then
  echo "python3 is required." >&2
  exit 1
fi

if ! have_cmd docker; then
  echo "Docker not found; attempting to install (Ubuntu/Debian)." >&2
  ensure_ubuntu_packages
fi

if ! docker compose version >/dev/null 2>&1; then
  echo "'docker compose' not found; attempting to install docker-compose-v2 (Ubuntu/Debian)." >&2
  ensure_ubuntu_packages
fi

ENV_CREATED=0
if [[ ! -f .env ]]; then
  echo "Creating .env with generated secrets..."
  cp .env.docker.example .env
  ENV_CREATED=1

  APP_KEY="$(random_key64)"
  DB_PASSWORD="$(random_safe_password)"
  MYSQL_ROOT_PASSWORD="$(random_safe_password)"

  # Best-effort URL default
  DEFAULT_HOST="localhost"
  if have_cmd hostname && hostname -I >/dev/null 2>&1; then
    DEFAULT_HOST="$(hostname -I | awk '{print $1}')"
  fi

  # Allow override via env at runtime: APP_URL and APP_PORT
  APP_URL_VALUE="${APP_URL:-http://${DEFAULT_HOST}}"
  APP_PORT_VALUE="${APP_PORT:-80}"

  # Update .env in-place
  python3 - <<PY
from pathlib import Path
env_path = Path('.env')
text = env_path.read_text(encoding='utf-8')
def set_kv(key, value):
    global text
    import re
    pattern = re.compile(rf'^{key}=.*$', re.M)
    replacement = f"{key}={value}"
    if pattern.search(text):
        text = pattern.sub(replacement, text)
    else:
        text += "\n" + replacement + "\n"

set_kv('APP_KEY', '${APP_KEY}')
set_kv('DB_PASSWORD', '${DB_PASSWORD}')
set_kv('MYSQL_ROOT_PASSWORD', '${MYSQL_ROOT_PASSWORD}')
set_kv('APP_URL', '${APP_URL_VALUE}')
set_kv('APP_PORT', '${APP_PORT_VALUE}')

env_path.write_text(text, encoding='utf-8')
print('Generated APP_KEY, DB_PASSWORD, MYSQL_ROOT_PASSWORD')
PY
fi

echo "Starting services..."
export COMPOSE_BAKE=false
sudo docker compose up -d --build

echo "Waiting for database to be ready..."
MYSQL_ROOT_PASSWORD_VALUE="$(grep -E '^MYSQL_ROOT_PASSWORD=' .env | head -n1 | cut -d= -f2- | tr -d '"\r')"
for i in {1..60}; do
  if sudo docker compose exec -T db mysqladmin ping -uroot -p"${MYSQL_ROOT_PASSWORD_VALUE}" --silent >/dev/null 2>&1; then
    break
  fi
  sleep 1
done

echo "Setting admin password..."
DB_DATABASE_VALUE="$(grep -E '^DB_DATABASE=' .env | head -n1 | cut -d= -f2- | tr -d '"\r')"
RESET_ADMIN_PASSWORD_VALUE="${RESET_ADMIN_PASSWORD:-0}"

if [[ "$ENV_CREATED" == "1" || "$RESET_ADMIN_PASSWORD_VALUE" == "1" ]]; then
  ADMIN_PASSWORD="${ADMIN_PASSWORD:-$(random_safe_password)}"
  sudo docker compose exec -T db mysql -uroot -p"${MYSQL_ROOT_PASSWORD_VALUE}" \
    -e "USE \`${DB_DATABASE_VALUE}\`; UPDATE admins SET password='${ADMIN_PASSWORD}' WHERE username='admin';" >/dev/null

  python3 - <<PY
from pathlib import Path
import re
env_path = Path('.env')
text = env_path.read_text(encoding='utf-8')
key = 'ADMIN_PASSWORD'
value = '${ADMIN_PASSWORD}'
pattern = re.compile(rf'^{key}=.*$', re.M)
replacement = f"{key}={value}"
if pattern.search(text):
    text = pattern.sub(replacement, text)
else:
    text += "\n" + replacement + "\n"
env_path.write_text(text, encoding='utf-8')
PY
else
  ADMIN_PASSWORD="(unchanged)"
fi

APP_URL_VALUE="$(grep -E '^APP_URL=' .env | head -n1 | cut -d= -f2- | tr -d '"\r')"
APP_PORT_VALUE="$(grep -E '^APP_PORT=' .env | head -n1 | cut -d= -f2- | tr -d '"\r')"

DISPLAY_URL="${APP_URL_VALUE%/}"
if [[ "$APP_PORT_VALUE" != "80" && "$DISPLAY_URL" != *":"* ]]; then
  DISPLAY_URL="${DISPLAY_URL}:${APP_PORT_VALUE}"
fi

echo ""
echo "========================================"
echo "SMMNepal deployed"
echo "========================================"
echo "Panel URL: ${DISPLAY_URL}"
echo "Admin URL: ${DISPLAY_URL}/admin"
echo "Admin user: admin"
echo "Admin pass: ${ADMIN_PASSWORD}"
echo ""
echo "To stop: sudo docker compose down"
echo "Logs: sudo docker compose logs -f --tail=200 web"
