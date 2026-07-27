FROM php:8.3-fpm AS base

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    nginx supervisor \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

FROM base AS dependencies

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY package.json package-lock.json ./
RUN npm ci --production

FROM base AS production

COPY . .

COPY --from=dependencies /var/www/vendor /var/www/vendor
COPY --from=dependencies /var/www/node_modules /var/www/node_modules

RUN composer dump-autoload --optimize --no-dev \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && npm run build \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
