#!/bin/sh

# Create the directory for the socket if it doesn't exist
mkdir -p /var/run
chown www-data:www-data /var/run

# 1. Map Render's dynamic port to Nginx
if [ -n "$PORT" ]; then
  sed -i "s/listen 80;/listen ${PORT};/" /etc/nginx/sites-available/default
fi

# 2. Fix permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Copy Render's secret CA cert to a readable location for PHP-FPM
if [ -f /etc/secrets/ca.pem ]; then
  cp /etc/secrets/ca.pem /var/www/ca.pem
  chown www-data:www-data /var/www/ca.pem
  chmod 644 /var/www/ca.pem
fi

# 3. CRITICAL: Don't let a failed migration stop the container from starting
# If migrations fail, we still want Nginx to start so we can see the logs
php artisan migrate --force || echo "Migration failed, but starting server anyway..."

# 4. Clear config to ensure Aiven credentials are fresh
php artisan config:clear

# 5. Start Supervisor
exec "$@"