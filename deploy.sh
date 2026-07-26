#!/bin/bash
set -euo pipefail

echo "🚀 SocialFeed Deployment Script"
echo "================================"

APP_DIR="/var/www/socialfeed"
BRANCH="${1:-main}"

echo "📦 Pulling latest code from $BRANCH..."
cd "$APP_DIR"
git fetch origin
git reset --hard "origin/$BRANCH"

echo "📥 Installing composer dependencies..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

echo "📥 Installing npm dependencies..."
npm ci --production

echo "🔧 Building assets..."
npm run build

echo "🔑 Generating app key (if needed)..."
php artisan key:generate --force --no-interaction

echo "⚙️  Caching config, routes, views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "🗄️  Running migrations..."
php artisan migrate --force

echo "🧹 Clearing old caches..."
php artisan cache:clear
php artisan config:cache

echo "📁 Setting permissions..."
chown -R www-data:www-data "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"

echo "🔄 Restarting queue workers..."
php artisan queue:restart

echo "✅ Deployment complete!"
echo "   App: $APP_DIR"
echo "   Branch: $BRANCH"
echo "   Time: $(date)"
