# Stage 1: Builder
FROM php:8.2-fpm-alpine AS builder

# Install required dependencies
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions gd zip @composer mysqli pdo_mysql xsl

# Set working directory
WORKDIR /app

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Stage 2: Production
FROM php:8.2-fpm-alpine

# Install minimal dependencies including Nginx and Supervisor
RUN apk add --no-cache nginx supervisor

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions gd zip @composer mysqli pdo_mysql xsl

# Set working directory
WORKDIR /var/www/html

# Copy application code from the builder
COPY --from=builder /app /var/www/html

# Copy configuration files
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf

# Create the log directory for Supervisor
RUN mkdir -p /var/log/supervisor

# KHUSUS KELOMPOK 6/KELOMPOK YANG SYMNLINKNYA RUSAK(CUKUP DI UNCOMMMENT)
RUN mkdir -p /var/www/html/storage/app/public \
    && mv /var/www/html/public/storage/* /var/www/html/storage/app/public/

# Symlink handling and permissions
RUN mkdir -p /var/www/html/public/storage \
    && rm -rf /var/www/html/public/storage \
    && php artisan storage:unlink \
    && php artisan storage:link \
        && chown -R www-data:www-data /var/www/html/storage /var/www/html/storage/app/public /var/www/html/public/storage \
    && chmod -R 775 /var/www/html/storage /var/www/html/storage/app/public

# Set mask user
RUN echo "umask 002" >> /etc/profile

# Expose port 80
EXPOSE 80

# Start Supervisor to manage PHP-FPM and Nginx
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]