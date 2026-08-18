#!/bin/bash
# Script ini dijalankan otomatis oleh Vercel saat build
set -e

echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

echo "🔧 Creating storage directories for Vercel /tmp..."
mkdir -p /tmp/storage/app/public
mkdir -p /tmp/storage/framework/cache/data
mkdir -p /tmp/storage/framework/sessions
mkdir -p /tmp/storage/framework/views
mkdir -p /tmp/storage/logs

echo "✅ Build complete!"
