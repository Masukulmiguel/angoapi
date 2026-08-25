FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev unzip git coreutils \
    && docker-php-ext-install pdo_pgsql zip bcmath opcache \
    && echo "precedence ::ffff:0:0/96 100" >> /etc/gai.conf \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts
COPY . .

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

EXPOSE 8000

CMD timeout 30 php artisan migrate --force || true; timeout 10 php artisan db:seed --force || true; exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
