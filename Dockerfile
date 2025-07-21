FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev zip libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy all source code (including artisan, env, etc)
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-progress --no-interaction

# Build assets (if needed)
RUN npm install && npm run build
