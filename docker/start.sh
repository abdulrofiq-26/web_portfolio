#!/bin/sh
set -e

echo "========================================="
echo " 🚀 Memulai deployment Laravel di Koyeb"
echo "========================================="

echo ""
echo "⚙️  [1/4] Caching config, routes, dan views..."
php /app/artisan config:cache
php /app/artisan route:cache
php /app/artisan view:cache

echo ""
echo "📦 [2/4] Menjalankan database migrations..."
php /app/artisan migrate --force

echo ""
echo "🔗 [3/4] Membuat storage symlink..."
php /app/artisan storage:link || true

echo ""
echo "✅ [4/4] Deployment selesai! Menjalankan Nginx + PHP-FPM..."
echo ""

# Start semua service via supervisord
exec /usr/bin/supervisord -c /etc/supervisord.conf
