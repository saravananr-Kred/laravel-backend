#!/bin/sh

# Ensure storage permissions are correct at runtime
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Run Laravel house-cleaning
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Hand over control to Supervisor
exec "$@"