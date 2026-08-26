FROM node:20-alpine AS frontend-build
WORKDIR /app/frontend
COPY frontend/package.json frontend/package-lock.json ./
RUN npm ci
COPY frontend/ ./
RUN npm run build

FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev unzip git coreutils \
    && docker-php-ext-install pdo_pgsql zip bcmath opcache \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts
COPY . .

RUN cp -n .env.production .env 2>/dev/null || cp -n .env.example .env 2>/dev/null || touch .env

COPY --from=frontend-build /app/frontend/dist /app/public/build

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

RUN echo "precedence ::ffff:0:0/96 100" >> /etc/gai.conf || true

EXPOSE 8000

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
