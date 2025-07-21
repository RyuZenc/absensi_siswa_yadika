FROM php:8.2-fpm

# Install dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev zip libonig-dev libpng-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set workdir
WORKDIR /var/www/html

# Copy source
COPY . .

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader --no-progress --no-interaction

# Build frontend (optional)
RUN npm install && npm run build
