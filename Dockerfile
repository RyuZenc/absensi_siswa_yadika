# === Stage 1: Build assets with Node.js ===
FROM node:20-alpine as nodebuild

WORKDIR /app

# Copy only necessary frontend files for build cache
COPY package*.json vite.config.* postcss.config.* tailwind.config.* ./

# Install dependencies
RUN npm install

# Copy assets
COPY resources/ resources/
COPY public/ public/

# Build frontend
RUN npm run build


# === Stage 2: PHP-Laravel base ===
FROM php:8.2-fpm

# Install PHP dependencies
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev zip libonig-dev libpng-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Laravel backend source
COPY . .

# Copy .env file (optional override for Railway)
RUN cp .env.railway .env

# Install backend dependencies (prod only)
RUN composer install --no-dev --optimize-autoloader --no-progress --no-interaction

# Copy built frontend from node stage
COPY --from=nodebuild /app/public/build ./public/build

# Set permissions (optional)
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
