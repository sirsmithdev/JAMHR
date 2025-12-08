# JamHR Laravel Application - Production Dockerfile
# Optimized for DigitalOcean App Platform

# ============================================
# Stage 1: Build frontend assets
# ============================================
FROM node:20-slim AS frontend

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm ci

# Copy frontend source files
COPY resources/ ./resources/
COPY vite.config.js postcss.config.js tailwind.config.js ./

# Build assets
RUN npm run build

# ============================================
# Stage 2: Install PHP dependencies
# ============================================
FROM composer:2 AS composer

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies without dev packages
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --ignore-platform-reqs

# Copy application code for autoloader optimization
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize --no-dev

# ============================================
# Stage 3: Production image
# ============================================
FROM php:8.2-fpm-alpine AS production

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    netcat-openbsd \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    postgresql-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
    && apk del --no-cache libpng-dev libjpeg-turbo-dev freetype-dev \
    && mkdir -p /run/nginx

# Install Redis extension
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Configure PHP for production
COPY docker/php.ini /usr/local/etc/php/conf.d/99-jamhr.ini

# Configure PHP-FPM
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/zz-jamhr.conf

# Configure Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Configure Supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set working directory
WORKDIR /var/www/html

# Copy application from build stages
COPY --from=composer /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

# Copy application code
COPY --chown=www-data:www-data . .

# Create required directories and set permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy entrypoint script
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Expose port
EXPOSE 8080

# Note: We run as root because supervisord needs to start nginx/php-fpm
# PHP-FPM and nginx handle their own user switching internally

# Entrypoint
ENTRYPOINT ["/entrypoint.sh"]

# Start supervisor
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
