#!/bin/sh

echo "Starting JamHR..."

# Create required directories
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Set permissions (don't fail if this doesn't work)
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# Storage link (ignore errors)
php artisan storage:link 2>/dev/null || true

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force 2>&1 || echo "Migration warning (may already be up to date)"

# Clear and cache config for production
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

echo "JamHR ready!"

# Execute the main command
exec "$@"
