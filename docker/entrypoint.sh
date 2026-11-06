#!/bin/sh
set -e

# Dynamically set Render's assigned $PORT (default to 10000)
PORT="${PORT:-10000}"
echo "==> Configuring Nginx to listen on port: $PORT"
sed -i "s/LISTEN_PORT/$PORT/g" /etc/nginx/http.d/default.conf

# Ensure storage directories exist
mkdir -p /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/storage/app/public

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create storage symlink
php artisan storage:link --force || true

# Optimize cache if APP_KEY is provided
if [ -n "$APP_KEY" ]; then
    echo "==> Caching configuration, routes, and views..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
else
    echo "==> WARNING: APP_KEY is not set. Please add APP_KEY in Render environment variables."
fi

# Optional auto-migrations if RUN_MIGRATIONS=true
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "==> Running database migrations..."
    php artisan migrate --force || true
fi

echo "==> Starting Nginx & PHP-FPM via Supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf

