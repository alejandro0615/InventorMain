#!/bin/bash

# Exit on error
set -e

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Cache configuration, events, routes, and views
echo "Caching configuration..."
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

# Start Apache
echo "Starting Apache..."
exec apache2-foreground
