# ==========================================
# Stage 1: Build Frontend Assets (Vite)
# ==========================================
FROM node:22-alpine AS frontend
WORKDIR /app

COPY package*.json ./
RUN npm ci --prefer-offline --no-audit || npm install

COPY vite.config.js ./
COPY resources/ resources/
COPY public/ public/

RUN npm run build

# ==========================================
# Stage 2: Install PHP & Composer Dependencies
# ==========================================
FROM composer:2 AS composer
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY . .
RUN composer dump-autoload --optimize --no-dev

# ==========================================
# Stage 3: Production Runtime (PHP 8.3 + Nginx)
# ==========================================
FROM php:8.3-fpm-alpine

# Install system packages & PHP extensions required by Laravel & PostgreSQL
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    bash \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        zip \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        opcache

# Set Nginx to run as www-data to match PHP-FPM user
RUN sed -i 's/user nginx;/user www-data;/g' /etc/nginx/nginx.conf

WORKDIR /var/www/html

# Copy vendor and application code from Composer stage
COPY --from=composer /app /var/www/html

# Copy compiled frontend assets from Frontend stage
COPY --from=frontend /app/public/build /var/www/html/public/build

# Copy configuration files
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Ensure proper permissions and strip any Windows CRLF line endings
RUN mkdir -p /tmp/nginx_client_body /tmp/nginx_fastcgi /var/lib/nginx/tmp/client_body /var/lib/nginx/tmp/fastcgi \
    && chown -R www-data:www-data /tmp/nginx_client_body /tmp/nginx_fastcgi /var/lib/nginx /var/log/nginx \
    && chmod -R 777 /tmp/nginx_client_body /tmp/nginx_fastcgi \
    && chmod +x /usr/local/bin/entrypoint.sh \
    && sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose Render default port
EXPOSE 10000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

