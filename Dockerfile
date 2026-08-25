FROM composer:latest AS build
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-autoloader
COPY . .
RUN composer dump-autoload --optimize --no-dev

FROM php:8.2-cli
RUN apt-get update && apt-get install -y libpq-dev libzip-dev unzip && docker-php-ext-install pdo_pgsql zip bcmath && rm -rf /var/lib/apt/lists/*
WORKDIR /var/www/html
COPY --from=build /app .
RUN mkdir -p storage bootstrap/cache && chmod -R 777 storage bootstrap/cache
EXPOSE 8000
CMD ["sh", "-c", "php artisan key:generate --force 2>/dev/null; php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=$PORT"]
