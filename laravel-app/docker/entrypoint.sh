#!/bin/sh
set -e

echo "Starting JamHR..."

# Create required runtime directories
mkdir -p /run
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Set permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Wait for database to be ready (optional, non-blocking)
if [ -n "$DATABASE_URL" ]; then
    echo "Checking database connection..."
    # Extract host and port from DATABASE_URL (handles postgres://user:pass@host:port/db?params format)
    DB_HOST=$(echo "$DATABASE_URL" | sed -n 's|.*@\([^:/?]*\).*|\1|p')
    DB_PORT=$(echo "$DATABASE_URL" | sed -n 's|.*:\([0-9]*\)/.*|\1|p')

    if [ -n "$DB_HOST" ] && [ -n "$DB_PORT" ]; then
        echo "Waiting for database at $DB_HOST:$DB_PORT..."
        for i in $(seq 1 30); do
            if nc -z "$DB_HOST" "$DB_PORT" 2>/dev/null; then
                echo "Database is ready!"
                break
            fi
            if [ $i -eq 30 ]; then
                echo "Warning: Database not ready after 30s, continuing anyway..."
            fi
            sleep 1
        done
    else
        echo "Could not parse database host/port, skipping wait..."
    fi
fi

# Cache configuration for production (with error handling)
if [ "$APP_ENV" = "production" ]; then
    echo "Caching configuration..."
    php artisan config:cache || echo "Warning: config:cache failed"
    php artisan route:cache || echo "Warning: route:cache failed"
    php artisan view:cache || echo "Warning: view:cache failed"
    php artisan event:cache || echo "Warning: event:cache failed"
fi

# Storage link (ignore errors if already exists)
php artisan storage:link 2>/dev/null || true

echo "JamHR started successfully!"

# Execute the main command
exec "$@"
