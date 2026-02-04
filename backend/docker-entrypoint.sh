#!/bin/bash
set -e

echo "Starting Laravel backend setup..."

# Wait for PostgreSQL to be ready
echo "Waiting for PostgreSQL..."
until php artisan db:show 2>/dev/null | grep -q "pgsql"; do
    echo "PostgreSQL is unavailable - sleeping"
    sleep 2
done

echo "PostgreSQL is up and running!"

# Install/update composer dependencies if needed
if [ ! -d "vendor" ]; then
    echo "Installing composer dependencies..."
    composer install --no-interaction --optimize-autoloader --no-dev
elif [ "composer.lock" -nt "vendor" ]; then
    echo "Updating composer dependencies..."
    composer install --no-interaction --optimize-autoloader --no-dev
fi

# Generate application key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Clear and cache config
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache

# Create storage link if it doesn't exist
if [ ! -L "public/storage" ]; then
    echo "Creating storage link..."
    php artisan storage:link
fi

echo "Laravel backend setup complete!"

# Start PHP-FPM
exec php-fpm
