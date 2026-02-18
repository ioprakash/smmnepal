#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

mkdir -p storage/logs storage/cache storage/sessions public/uploads public/cache
chown -R www-data:www-data storage public/uploads public/cache || true

if [[ ! -f .env && -f .env.example ]]; then
  cp .env.example .env
fi

exec "$@"
