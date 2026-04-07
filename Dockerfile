# --- PHP Dependencies ---
FROM serversideup/php:8.4-fpm-nginx AS vendor
WORKDIR /var/www/html
USER root
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# --- Build Assets ---
FROM node:20-alpine AS assets
WORKDIR /app

COPY package*.json ./
RUN npm install
COPY . .
# Copiamos el folder vendor generado en la etapa anterior para que Ziggy sea resoluble
COPY --from=vendor /var/www/html/vendor ./vendor
RUN npm run build

# --- App Runtime ---
FROM serversideup/php:8.4-fpm-nginx AS app

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
USER root
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    zip \
    libzip-dev \
    unzip \
    git \
    curl \
    libicu-dev \
    supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configuración de Variables de Entorno para Producción
ENV AUTORUN_ENABLED=true
ENV PHP_OPCACHE_ENABLE=1
ENV APP_ENV=production
ENV APP_DEBUG=false

# Copy application files (all source)
COPY --chown=www-data:www-data . .

# Copy built vendor and assets from previous stages
COPY --from=vendor --chown=www-data:www-data /var/www/html/vendor ./vendor
COPY --from=assets --chown=www-data:www-data /app/public/build ./public/build

# Ensure relevant storage paths exist and are writable
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port
EXPOSE 80

# Nota: Para Reverb en Dokploy, se recomienda usar el comando 'php artisan reverb:start' 
# como un servicio separado o configurar un worker en el panel de Dokploy.
