#!/bin/sh
set -e

echo "🚀 Starting Laravel deployment..."

# Run Laravel artisan commands
echo "⚙️  Caching config, routes, and views..."
php /app/artisan config:cache
php /app/artisan route:cache
php /app/artisan view:cache

echo "📦 Running database migrations..."
php /app/artisan migrate --force

echo "🔗 Creating storage symlink..."
php /app/artisan storage:link || true

echo "✅ Deployment complete! Starting services..."

# Start Nginx + PHP-FPM via supervisord
exec /usr/bin/supervisord -c /etc/supervisord.conf
