FROM php:8.3-fpm

# Install useful PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html

# Copy application code
COPY src/ ./