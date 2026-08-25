#!/bin/sh
set -e

php artisan key:generate --force 2>/dev/null || true
php artisan migrate --force
php artisan db:seed --force 2>/dev/null || true

php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
