FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev zip libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-progress --no-interaction

RUN npm install && npm run build
