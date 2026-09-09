#!/bin/bash

set -e

echo "Running migrations..."
php artisan migrate --force

echo "Clearing Laravel caches..."
php artisan optimize:clear

echo "Rebuilding caches..."
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

echo "Starting Apache..."
exec apache2-foreground
