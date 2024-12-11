# Stage 1: Builder
FROM php:8.2-fpm-alpine AS builder

# Install required dependencies
RUN apk add --no-cache \
    unzip \
    git \
    libzip-dev \
    && docker-php-ext-install zip

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
RUN apk add --no-cache \
    libzip-dev \
    nginx \
    supervisor \
    && docker-php-ext-install zip

# Set working directory
WORKDIR /var/www/html

# Copy application code from the builder
COPY --from=builder /app /var/www/html

# Copy configuration files
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf

# Create the log directory for Supervisor
RUN mkdir -p /var/log/supervisor

# Optimize permission changes
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
&& chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
&& chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Start Supervisor to manage PHP-FPM and Nginx
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]