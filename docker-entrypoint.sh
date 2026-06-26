#!/bin/bash
cd /var/www/html

echo "=============================="
echo " ASOIINFO Platform - Startup"
echo "=============================="

# ── 1. Generate APP_KEY with pure PHP (no artisan needed) ─────────────────────
# Render sets APP_KEY via generateValue:true — only fall back if truly missing.
if [ -z "$APP_KEY" ]; then
  APP_KEY=$(php -r "echo 'base64:'.base64_encode(random_bytes(32));" 2>/dev/null)
  echo "[ok] APP_KEY generated from PHP random_bytes"
else
  echo "[ok] APP_KEY from Render environment"
fi

# ── 2. Write .env ─────────────────────────────────────────────────────────────
{
  echo "APP_NAME=${APP_NAME:-ASOIINFO Platform}"
  echo "APP_ENV=${APP_ENV:-production}"
  echo "APP_KEY=$APP_KEY"
  echo "APP_DEBUG=false"
  echo "APP_URL=${APP_URL:-https://asoiinfo-platform-1.onrender.com}"
  echo "LOG_CHANNEL=${LOG_CHANNEL:-stderr}"
  echo "LOG_LEVEL=error"
  echo "DB_CONNECTION=${DB_CONNECTION:-pgsql}"
  echo "DB_HOST=${DB_HOST:-dpg-d8sqptvavr4c73arvuq0-a}"
  echo "DB_PORT=${DB_PORT:-5432}"
  echo "DB_DATABASE=${DB_DATABASE:-asoiinfo_db}"
  echo "DB_USERNAME=${DB_USERNAME:-asoiinfo_db_user}"
  echo "DB_PASSWORD=${DB_PASSWORD:-3rnRd1xnZdM6NZA3Tqcgq6e8lKHx2wJa}"
  echo "SESSION_DRIVER=cookie"
  echo "SESSION_LIFETIME=120"
  echo "SESSION_SECURE_COOKIE=true"
  echo "CACHE_STORE=file"
  echo "QUEUE_CONNECTION=sync"
  echo "TRUSTED_PROXIES=*"
} > .env
echo "[ok] .env written — DB_HOST=${DB_HOST:-dpg-d8sqptvavr4c73arvuq0-a}"

# ── 3. Clear stale bootstrap cache ───────────────────────────────────────────
php artisan config:clear 2>/dev/null && echo "[ok] config cleared" || echo "[skip] config:clear"
php artisan route:clear  2>/dev/null && echo "[ok] route cleared"  || echo "[skip] route:clear"
php artisan view:clear   2>/dev/null && echo "[ok] view cleared"   || echo "[skip] view:clear"

# ── 4. Migrate (non-fatal) ───────────────────────────────────────────────────
echo "[...] Running migrations..."
if php artisan migrate --force --no-interaction 2>&1; then
  echo "[ok] Migrations complete"
else
  echo "[WARN] Migrations failed — app may show DB errors"
fi

# ── 5. Seed once if users table is empty ─────────────────────────────────────
USER_COUNT=$(php -r "
try {
  \$h  = getenv('DB_HOST')     ?: 'dpg-d8sqptvavr4c73arvuq0-a';
  \$p  = getenv('DB_PORT')     ?: '5432';
  \$db = getenv('DB_DATABASE') ?: 'asoiinfo_db';
  \$u  = getenv('DB_USERNAME') ?: 'asoiinfo_db_user';
  \$pw = getenv('DB_PASSWORD') ?: '3rnRd1xnZdM6NZA3Tqcgq6e8lKHx2wJa';
  \$pdo = new PDO(\"pgsql:host=\$h;port=\$p;dbname=\$db\", \$u, \$pw, [PDO::ATTR_TIMEOUT=>5]);
  echo (int)\$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
} catch(Exception \$e){ echo 0; }
" 2>/dev/null)
USER_COUNT="${USER_COUNT:-0}"
echo "[info] User count: $USER_COUNT"

if [ "$USER_COUNT" = "0" ]; then
  echo "[...] Seeding..."
  php artisan db:seed --force --no-interaction 2>&1 && echo "[ok] Seeded" || echo "[warn] Seed failed"
fi

# ── 6. Cache for production ───────────────────────────────────────────────────
php artisan config:cache 2>/dev/null && echo "[ok] config cached" || echo "[skip] config:cache"
php artisan route:cache  2>/dev/null && echo "[ok] route cached"  || echo "[skip] route:cache"
php artisan view:cache   2>/dev/null && echo "[ok] view cached"   || echo "[skip] view:cache"

echo "=============================="
echo " Starting Apache"
echo "=============================="
exec apache2-foreground
