#!/usr/bin/env bash

# ==============================================================================
# Belajar Dong - Quick Deployment / Update Script for VPS
# Usage: cd /var/www/belajardong && bash deploy.sh
# ==============================================================================

set -e

GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${BLUE}============================================================${NC}"
echo -e "${BLUE}       BELAJAR DONG - DEPLOYMENT / UPDATE SCRIPT            ${NC}"
echo -e "${BLUE}============================================================${NC}"

# Check root or proper permissions
if [ "$EUID" -ne 0 ]; then
    echo -e "${YELLOW}[INFO] Script disarankan dijalankan sebagai sudo / root untuk reload service.${NC}"
fi

# 1. Maintenance Mode
echo -e "${YELLOW}[1/6] Mengaktifkan Maintenance Mode...${NC}"
php artisan down --render="errors::503" || true

# 2. Pull Git Repository
echo -e "${YELLOW}[2/6] Menarik update kode terbaru dari repository...${NC}"
git reset --hard
git pull origin main

# 3. Install PHP Dependencies
echo -e "${YELLOW}[3/6] Memperbarui dependensi Composer...${NC}"
export COMPOSER_ALLOW_SUPERUSER=1
composer install --no-dev --optimize-autoloader --no-interaction

# 4. Run Database Migrations
echo -e "${YELLOW}[4/6] Menjalankan migrasi database...${NC}"
php artisan migrate --force

# 5. Build Frontend Assets
echo -e "${YELLOW}[5/6] Mengompilasi aset frontend (Vite)...${NC}"
npm install --ignore-scripts
npm run build

# 6. Optimize and Clear Cache
echo -e "${YELLOW}[6/6] Memperbarui cache aplikasi Laravel...${NC}"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link || true

# Permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
if [ -f database/database.sqlite ]; then
    chmod 775 database
    chmod 664 database/database.sqlite
    chown www-data:www-data database/database.sqlite
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
echo -e "${GREEN}  UPDATE BELAJAR DONG BERHASIL DITERAPKAN DI VPS!           ${NC}"
echo -e "${GREEN}============================================================${NC}"
