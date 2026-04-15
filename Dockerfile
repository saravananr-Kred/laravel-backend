# Use official PHP image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies + NGINX and SUPERVISOR
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    nginx supervisor 

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# --- CONFIGURATION STEPS ---

# 1. Copy Nginx config (You must create this file in your root)
COPY ./nginx.conf /etc/nginx/sites-available/default

# 2. Copy Supervisor config (You must create this file in your root)
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set correct permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Expose ports (80 for Web, 8080 for Reverb internal)
EXPOSE 80 8080

RUN sed -i 's/listen = \/usr\/local\/var\/run\/php-fpm.sock/listen = 127.0.0.1:9000/' /usr/local/etc/php-fpm.d/www.conf

# Start Supervisor (This starts Nginx, PHP-FPM, Reverb, and Queue)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]