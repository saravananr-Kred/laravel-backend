#!/bin/sh

# 1. This is the MOST important line for Render
# It replaces "listen 80;" with "listen [Render's Dynamic Port];"
sed -i "s/listen 80;/listen ${PORT:-80};/" /etc/nginx/sites-available/default

# 2. Permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# 3. Clear Caches (Don't use 'config:cache' on Render, it can freeze old ENV values)
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 4. Database
php artisan migrate --force

# 5. Start
exec "$@"