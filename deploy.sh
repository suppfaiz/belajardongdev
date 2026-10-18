#!/usr/bin/env bash

# ==============================================================================
# BelajarYuk - Quick Deployment / Update Script for VPS
# Usage: cd /var/www/belajardong && bash deploy.sh
# ==============================================================================

set -e

GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${BLUE}============================================================${NC}"
echo -e "${BLUE}         BELAJARYUK - DEPLOYMENT / UPDATE SCRIPT            ${NC}"
echo -e "${BLUE}============================================================${NC}"

# Ensure standard PATH for composer, npm, git, php
export PATH="/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin:$PATH"
git config --global --add safe.directory "$(pwd)" 2>/dev/null || true

# Always ensure app is UP in case of previous interrupted deploy
php artisan up 2>/dev/null || true

# 1. Pull Git Repository
echo -e "${YELLOW}[1/5] Menarik update kode terbaru dari repository...${NC}"
git reset --hard
git pull origin main

# 2. Install PHP Dependencies
echo -e "${YELLOW}[2/5] Memperbarui dependensi Composer...${NC}"
export COMPOSER_ALLOW_SUPERUSER=1
composer install --no-dev --optimize-autoloader --no-interaction

# 3. Run Database Migrations
echo -e "${YELLOW}[3/5] Menjalankan migrasi database...${NC}"
php artisan migrate --force

# 4. Build Frontend Assets
echo -e "${YELLOW}[4/5] Mengompilasi aset frontend (Vite)...${NC}"
npm install --ignore-scripts
npm run build

# 5. Optimize and Clear Cache
echo -e "${YELLOW}[5/5] Memperbarui cache aplikasi Laravel...${NC}"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link || true

# Permissions
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
if [ -f database/database.sqlite ]; then
    chmod 775 database 2>/dev/null || true
    chmod 664 database/database.sqlite 2>/dev/null || true
    chown www-data:www-data database/database.sqlite 2>/dev/null || true
fi

# Reload services if systemctl is available
if command -v systemctl &> /dev/null; then
    systemctl reload php8.4-fpm || systemctl reload php8.3-fpm || true
    systemctl reload nginx || true
fi

# Disable Maintenance Mode
php artisan up

echo ""
echo -e "${GREEN}============================================================${NC}"
echo -e "${GREEN}    UPDATE BELAJARYUK BERHASIL DITERAPKAN DI VPS!           ${NC}"
echo -e "${GREEN}============================================================${NC}"
