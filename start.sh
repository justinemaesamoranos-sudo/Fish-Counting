#!/bin/bash
set -e

cd /app

php artisan migrate --force
php artisan storage:link 2>/dev/null || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

php -S 0.0.0.0:${PORT:-8080} -t public public/index.php
