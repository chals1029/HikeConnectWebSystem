#!/usr/bin/env bash
# Run ON the VPS from the Laravel project root (same directory as artisan).
# Usage:
#   cd /path/to/HikeConnectWebSystem && sudo bash scripts/vps-fix-storage.sh
# Or without sudo if you already own the files:
#   cd /path/to/HikeConnectWebSystem && bash scripts/vps-fix-storage.sh
#
# Optional: force web user for chown (Debian/Ubuntu PHP-FPM is often www-data):
#   WEB_USER=www-data sudo -E bash scripts/vps-fix-storage.sh

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

echo "Project root: $ROOT"

mkdir -p storage/app/public \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/views \
  storage/logs \
  bootstrap/cache

# Symlink public/storage -> storage/app/public (safe to re-run)
if [[ ! -e public/storage ]]; then
  php artisan storage:link
else
  echo "public/storage already exists, skipping storage:link"
fi

chmod -R ug+rwX storage bootstrap/cache

if [[ -n "${WEB_USER:-}" ]]; then
  echo "chown -R $WEB_USER:$WEB_USER storage bootstrap/cache"
  chown -R "$WEB_USER:$WEB_USER" storage bootstrap/cache
fi

php artisan migrate --force

php artisan optimize:clear || true
php artisan config:cache || true

echo "Done. Profile pictures are stored in DB (user_profile_pictures). Legacy files still use storage/app/public."
echo "Ensure PHP upload_max_filesize / post_max_size and nginx client_max_body_size allow your image size (e.g. 12M)."
