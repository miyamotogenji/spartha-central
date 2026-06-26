#!/bin/bash
# NO set -e — we handle errors explicitly so one failed step can't kill the container
cd /var/www/html

echo "=== ASOIINFO Platform startup ==="

# ── 1. Write .env (echo-based, no heredoc, no CRLF risk) ─────────────
{
  echo "APP_NAME=${APP_NAME:-ASOIINFO Platform}"
  echo "APP_ENV=${APP_ENV:-production}"
  echo "APP_KEY=${APP_KEY:-}"
  echo "APP_DEBUG=${APP_DEBUG:-false}"
  echo "APP_URL=${APP_URL:-http://localhost}"
  echo "LOG_CHANNEL=${LOG_CHANNEL:-stderr}"
  echo "LOG_LEVEL=error"
  echo "DB_CONNECTION=${DB_CONNECTION:-pgsql}"
  echo "DB_HOST=${DB_HOST:-127.0.0.1}"
  echo "DB_PORT=${DB_PORT:-5432}"
  echo "DB_DATABASE=${DB_DATABASE:-asoiinfo_db}"
  echo "DB_USERNAME=${DB_USERNAME:-asoiinfo_db_user}"
  echo "DB_PASSWORD=${DB_PASSWORD:-}"
  echo "SESSION_DRIVER=${SESSION_DRIVER:-cookie}"
  echo "SESSION_LIFETIME=120"
  echo "SESSION_SECURE_COOKIE=true"
  echo "CACHE_STORE=${CACHE_STORE:-database}"
  echo "QUEUE_CONNECTION=sync"
  echo "TRUSTED_PROXIES=*"
} > .env
echo "  [ok] .env written"

# ── 2. Generate APP_KEY only if not set by Render ─────────────────────
if [ -z "$APP_KEY" ]; then
  php artisan key:generate --force --no-interaction 2>&1 && echo "  [ok] APP_KEY generated" || echo "  [warn] key:generate failed"
else
  echo "  [ok] APP_KEY from environment"
fi

# ── 3. Clear stale caches (all non-fatal) ────────────────────────────
php artisan config:clear 2>/dev/null && echo "  [ok] config cleared" || echo "  [skip] config:clear"
php artisan cache:clear  2>/dev/null && echo "  [ok] cache cleared"  || echo "  [skip] cache:clear"
php artisan route:clear  2>/dev/null && echo "  [ok] route cleared"  || echo "  [skip] route:clear"
php artisan view:clear   2>/dev/null && echo "  [ok] view cleared"   || echo "  [skip] view:clear"

# ── 4. Migrate (fatal — DB must be reachable) ────────────────────────
echo "  Running migrations..."
if php artisan migrate --force --no-interaction 2>&1; then
  echo "  [ok] Migrations done"
else
  echo "  [ERROR] Migrations failed — check DB env vars"
  echo "    DB_HOST=$DB_HOST  DB_PORT=$DB_PORT  DB_DATABASE=$DB_DATABASE  DB_USERNAME=$DB_USERNAME"
  exit 1
fi

# ── 5. Seed once (using plain PDO — no artisan overhead) ─────────────
USER_COUNT=$(php -r "
  try {
    \$h = getenv('DB_HOST')     ?: '127.0.0.1';
    \$p = getenv('DB_PORT')     ?: '5432';
    \$d = getenv('DB_DATABASE') ?: 'asoiinfo_db';
    \$u = getenv('DB_USERNAME') ?: 'asoiinfo_db_user';
    \$pw= getenv('DB_PASSWORD') ?: '';
    \$pdo = new PDO(\"pgsql:host=\$h;port=\$p;dbname=\$d\", \$u, \$pw, [PDO::ATTR_TIMEOUT=>5]);
    echo (int)\$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
  } catch(Exception \$e){ echo 0; }
" 2>/dev/null)

USER_COUNT=${USER_COUNT:-0}
echo "  User count: $USER_COUNT"

if [ "$USER_COUNT" = "0" ]; then
  echo "  Seeding database..."
  php artisan db:seed --force --no-interaction 2>&1 \
    && echo "  [ok] Seeded" \
    || echo "  [skip] Seed failed — continuing"
fi

# ── 6. Cache for production (non-fatal) ──────────────────────────────
php artisan config:cache 2>/dev/null && echo "  [ok] config cached" || echo "  [skip] config:cache"
php artisan route:cache  2>/dev/null && echo "  [ok] route cached"  || echo "  [skip] route:cache"
php artisan view:cache   2>/dev/null && echo "  [ok] view cached"   || echo "  [skip] view:cache"

echo "=== Starting Apache ==="
exec apache2-foreground
