FROM php:8.2-fpm

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev zip \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# 👇 Penting: salin file composer lebih awal agar bisa `install` dulu
COPY composer.json composer.lock ./

RUN composer install --ignore-platform-reqs --no-progress --no-interaction --verbose

# Setelah itu baru salin semua isi app
COPY . .

# NPM build (jika kamu pakai Node)
RUN npm install
RUN npm run build
