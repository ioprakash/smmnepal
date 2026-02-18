# Deployment

This project is a plain PHP (Apache) app. Configuration is read from:
- Environment variables (`DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `APP_URL`, `APP_DEBUG`)
- Or a root `.env` file (same keys), if present

## Option A: Docker Compose (recommended for VPS)

### One-click (fresh machine)

On a new Ubuntu VPS, you can deploy with:

```bash
git clone https://github.com/ioprakash/smmnepal.git
cd smmnepal
chmod +x scripts/oneclick-deploy.sh
./scripts/oneclick-deploy.sh
```

This will:
- Install Docker + `docker compose` (Ubuntu/Debian)
- Create `.env` from `.env.docker.example` (if missing)
- Generate `APP_KEY`, DB passwords, and set a new admin password
- Start `web` + `db`
- Print the Panel URL + Admin credentials

Alternative (same thing):

```bash
make deploy
```

### A1) VPS + existing MySQL (production)

On your VPS (Ubuntu/Debian example):

1. Install Docker + Compose plugin:

```bash
sudo apt-get update
sudo apt-get install -y ca-certificates curl
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg

echo \
	"deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
	$(. /etc/os-release && echo \"$VERSION_CODENAME\") stable" \
	| sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

sudo apt-get update
sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
sudo systemctl enable --now docker
```

2. Clone and configure:

```bash
git clone https://github.com/ioprakash/smmnepal.git
cd smmnepal
cp .env.example .env
```

3. Edit `.env` and set (required):

```env
APP_URL=https://nepalboost.com
APP_DEBUG=false

DB_HOST=YOUR_DB_HOST
DB_DATABASE=YOUR_DB_NAME
DB_USERNAME=YOUR_DB_USER
DB_PASSWORD=YOUR_DB_PASS
DB_CHARSET=utf8mb4
```

4. Start:

```bash
sudo docker compose up -d --build
sudo docker compose ps
```

5. Check logs if needed:

```bash
sudo docker compose logs -f --tail=200 web
```

If your database is empty, import `Database.sql` into your existing MySQL (from wherever you manage it).

### A2) Local dev (optional)

If you want a local DB for development, run MySQL separately or add a DB service back for dev only.

---

From the repo root (local machine):

```bash
cp .env.example .env
```

2. Edit `.env` (or rely on the defaults in `docker-compose.yml`). At minimum set:

```env
APP_URL=http://localhost:8080
DB_HOST=db
DB_DATABASE=smmnepal
DB_USERNAME=smmnepal
DB_PASSWORD=smmnepal
```

3. Start services:

```bash
docker compose up --build
```

- App: `http://localhost:8080`
- Database schema is auto-imported from `Database.sql` on first boot.

## Option B: cPanel / shared hosting

1. Upload the code to `public_html/`.
2. Create a MySQL database + user and grant privileges.
3. Create `.env` in the project root (same folder as `index.php`):

```env
APP_URL=https://yourdomain.com
APP_DEBUG=false

DB_HOST=localhost
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_password
DB_CHARSET=utf8mb4
```

4. Import `Database.sql` into your database.
5. Ensure writable folders exist:

```bash
mkdir -p storage/logs storage/cache storage/sessions public/uploads public/cache
chmod -R 755 storage public
```

If SMTP email is needed, configure SMTP fields in the panel settings (`smtp_server`, `smtp_user`, `smtp_pass`, `smtp_port`, `smtp_protocol`).
