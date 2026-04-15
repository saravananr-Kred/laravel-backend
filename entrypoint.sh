#!/bin/sh

# 1. Map Render's dynamic port to Nginx
if [ -n "$PORT" ]; then
  sed -i "s/listen 80;/listen ${PORT};/" /etc/nginx/sites-available/default
fi

# 2. Fix permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# 3. CRITICAL: Don't let a failed migration stop the container from starting
# If migrations fail, we still want Nginx to start so we can see the logs
php artisan migrate --force || echo "Migration failed, but starting server anyway..."

# 4. Clear config to ensure Aiven credentials are fresh
php artisan config:clear

# 5. Start Supervisor
exec "$@"