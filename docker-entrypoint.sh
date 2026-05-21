#!/bin/bash
set -e

# Check if .env file exists, if not, copy from env.contoh
if [ ! -f .env ]; then
    echo ".env file not found. Copying from env.contoh..."
    cp env.contoh .env
fi

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ] && ! grep -q "^APP_KEY=" .env; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Run database seeders
echo "Running database seeders..."
php artisan db:seed --force

# Clear and optimize cache
echo "Clearing and optimizing cache..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Application is ready!"

# Execute main command
exec "$@"
