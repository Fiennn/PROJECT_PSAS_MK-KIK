#!/bin/sh
set -e

echo "Starting deployment setup..."

# Cache config, route, view
php artisan config:clear
php artisan cache:clear

# Run database migrations and seeders if tables don't exist
echo "Running migrations..."
php artisan migrate --force --isolated

# Optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Link storage
php artisan storage:link || true

echo "Starting Nginx & PHP-FPM on port ${PORT:-80}..."
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
