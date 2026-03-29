#!/bin/bash
set -e

cd /app

php artisan migrate --force

# Create storage directory and copy instead of symlink (symlinks don't work with php -S)
mkdir -p public/storage
cp -r storage/app/public/. public/storage/ 2>/dev/null || true

php artisan config:cache
php artisan route:cache
php artisan view:cache

php -S 0.0.0.0:${PORT:-8080} server.php
