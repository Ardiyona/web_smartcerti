# Stage 1: Builder
FROM php:8.2-fpm-alpine AS builder

# Install required dependencies
RUN apk add --no-cache unzip git libzip-dev freetype-dev libjpeg-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip gd mysqli pdo pdo_mysql \
    && docker-php-ext-enable pdo_mysql

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Stage 2: Production
FROM php:8.2-fpm-alpine

# Install minimal dependencies including Nginx and Supervisor
RUN apk add --no-cache libzip-dev freetype-dev libjpeg-turbo-dev libpng-dev nginx supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip gd mysqli pdo pdo_mysql \
    && docker-php-ext-enable pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy application code from the builder
COPY --from=builder /app /var/www/html

# Copy configuration files
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf

# Create the log directory for Supervisor
RUN mkdir -p /var/log/supervisor

# Symlink handling and permissions
RUN mkdir /var/www/html/temp_storage \
    && mv /var/www/html/public/storage /var/www/html/temp_storage \
    && rm -rf /var/www/html/public/storage \
    && php artisan storage:unlink \
    && php artisan storage:link \
    && mkdir -p /var/html/storage/app/public \
    && mv /var/www/html/temp_storage/* /var/www/html/storage/app/public/ \
    && rm -rf /var/www/html/temp_storage \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Set mask user
RUN echo "umask 002" >> /etc/profile

# Expose port 80
EXPOSE 80

# Start Supervisor to manage PHP-FPM and Nginx
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]