#!/usr/bin/env bash

# ==============================================================================
# Belajar Dong - One-Click VPS Installer Script
# Target OS: Ubuntu 22.04 LTS / 24.04 LTS / Debian 11/12
# Repository: https://github.com/suppfaiz/belajardongdev.git
# ==============================================================================

set -e

# Colors for terminal output
RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

echo -e "${BLUE}"
echo "============================================================"
echo "          BELAJAR DONG - ONE CLICK VPS INSTALLER            "
echo "============================================================"
echo -e "${NC}"

# 1. Root check
if [ "$EUID" -ne 0 ]; then
    echo -e "${RED}[ERROR] Script ini harus dijalankan sebagai root (gunakan sudo bash install.sh)${NC}"
    exit 1
fi

# 2. Get Public IP or Domain
SERVER_IP=$(curl -s -4 ifconfig.me || hostname -I | awk '{print $1}')
echo -e "${CYAN}IP Publik Server Anda terdeteksi: ${YELLOW}${SERVER_IP}${NC}"

if [ -n "$DOMAIN" ]; then
    USER_DOMAIN="$DOMAIN"
elif [ -e /dev/tty ]; then
    read -rp "Masukkan Domain Anda (kosongkan jika ingin menggunakan IP: ${SERVER_IP}): " USER_DOMAIN < /dev/tty || true
else
    USER_DOMAIN=""
fi

DOMAIN_OR_IP=${USER_DOMAIN:-$SERVER_IP}

# 3. Choose Database Driver
if [ -n "$DB_DRIVER" ]; then
    DB_CHOICE="$DB_DRIVER"
elif [ -e /dev/tty ]; then
    echo ""
    echo -e "${CYAN}Pilih Database Driver:${NC}"
    echo "1) SQLite (Rekomendasi Cepat & Siap Pakai tanpa konfigurasi rumit)"
    echo "2) PostgreSQL (Standar Produksi Skala Besar)"
    read -rp "Pilihan [1/2] (Default: 1): " DB_CHOICE < /dev/tty || true
else
    DB_CHOICE="1"
fi
DB_CHOICE=${DB_CHOICE:-1}

# 4. System Update & Dependencies
echo ""
echo -e "${YELLOW}[1/7] Memperbarui paket sistem Ubuntu & memasang dependensi dasar...${NC}"
export DEBIAN_FRONTEND=noninteractive
apt-get update -y
apt-get install -y curl wget git unzip software-properties-common lsb-release ca-certificates apt-transport-https gnupg2 build-essential ufw

# 5. Install Nginx
echo ""
echo -e "${YELLOW}[2/7] Memasang dan mengonfigurasi Web Server Nginx...${NC}"
apt-get install -y nginx
systemctl enable nginx
systemctl start nginx

# 6. Install PHP 8.3 & Extensions
echo ""
echo -e "${YELLOW}[3/7] Memasang PHP 8.3 dan ekstensi yang diperlukan...${NC}"
add-apt-repository -y ppa:ondrej/php || true
apt-get update -y
apt-get install -y php8.3-fpm php8.3-cli php8.3-common php8.3-mbstring php8.3-xml \
    php8.3-curl php8.3-zip php8.3-bcmath php8.3-intl php8.3-gd php8.3-sqlite3 \
    php8.3-pgsql php8.3-mysql php8.3-tokenizer

systemctl enable php8.3-fpm
systemctl start php8.3-fpm

# 7. Install Composer
echo ""
echo -e "${YELLOW}[4/7] Memasang Composer...${NC}"
if ! command -v composer &> /dev/null; then
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi

# 8. Install Node.js (v20 LTS) & NPM
echo ""
echo -e "${YELLOW}[5/7] Memasang Node.js LTS (v20) & NPM...${NC}"
if ! command -v node &> /dev/null; then
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    apt-get install -y nodejs
fi

# 9. Clone / Update Application Repository
APP_DIR="/var/www/belajardong"
echo ""
echo -e "${YELLOW}[6/7] Menyiapkan source code aplikasi di ${APP_DIR}...${NC}"

if [ -d "$APP_DIR/.git" ]; then
    echo "Direktori sudah ada, memperbarui dari git repository..."
    cd "$APP_DIR"
    git reset --hard
    git pull origin main
else
    mkdir -p /var/www
    cd /var/www
    rm -rf "$APP_DIR"
    git clone https://github.com/suppfaiz/belajardongdev.git belajardong
    cd "$APP_DIR"
fi

# 10. Configure Environment (.env)
echo "Mengatur konfigurasi environment (.env)..."
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Update APP_URL & APP_NAME
sed -i "s|^APP_NAME=.*|APP_NAME=\"Belajar Dong\"|g" .env
sed -i "s|^APP_ENV=.*|APP_ENV=production|g" .env
sed -i "s|^APP_DEBUG=.*|APP_DEBUG=false|g" .env
sed -i "s|^APP_URL=.*|APP_URL=http://${DOMAIN_OR_IP}|g" .env

# Configure Database
if [ "$DB_CHOICE" == "2" ]; then
    echo "Memasang PostgreSQL..."
    apt-get install -y postgresql postgresql-contrib
    systemctl enable postgresql
    systemctl start postgresql

    DB_NAME="belajardong"
    DB_USER="belajaruser"
    DB_PASS=$(openssl rand -base64 12 | tr -dc 'a-zA-Z0-9' | head -c 16)

    sudo -u postgres psql -c "CREATE USER ${DB_USER} WITH PASSWORD '${DB_PASS}';" || true
    sudo -u postgres psql -c "CREATE DATABASE ${DB_NAME} OWNER ${DB_USER};" || true
    sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE ${DB_NAME} TO ${DB_USER};" || true

    sed -i "s|^DB_CONNECTION=.*|DB_CONNECTION=pgsql|g" .env
    sed -i "s|^# DB_HOST=.*|DB_HOST=127.0.0.1|g" .env
    sed -i "s|^# DB_PORT=.*|DB_PORT=5432|g" .env
    sed -i "s|^# DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|g" .env
    sed -i "s|^# DB_USERNAME=.*|DB_USERNAME=${DB_USER}|g" .env
    sed -i "s|^# DB_PASSWORD=.*|DB_PASSWORD=${DB_PASS}|g" .env
else
    sed -i "s|^DB_CONNECTION=.*|DB_CONNECTION=sqlite|g" .env
    mkdir -p database
    touch database/database.sqlite
fi

# 11. Composer Install & Build Frontend
echo "Menjalankan Composer install & build aset Vite..."
composer install --no-dev --optimize-autoloader --no-interaction
php artisan key:generate --force
php artisan storage:link || true

npm install --ignore-scripts
npm run build

# 12. Run Migrations & Seeders
echo "Menjalankan migrasi database & data awal (Seeder)..."
php artisan migrate --force
php artisan db:seed --force || true

# 13. Optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 14. Configure Nginx Virtual Host
echo ""
echo -e "${YELLOW}[7/7] Membuat konfigurasi Nginx untuk Belajar Dong...${NC}"

cat > /etc/nginx/sites-available/belajardong <<EOF
server {
    listen 80;
    listen [::]:80;
    server_name ${DOMAIN_OR_IP};
    root ${APP_DIR}/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php index.html;
    charset utf-8;

    client_max_body_size 64M;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

ln -sf /etc/nginx/sites-available/belajardong /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test Nginx and restart
nginx -t
systemctl reload nginx

# 15. Set File Permissions
echo "Mengatur hak akses dan kepemilikan direktori..."
chown -R www-data:www-data "$APP_DIR"
find "$APP_DIR" -type f -exec chmod 664 {} \;
find "$APP_DIR" -type d -exec chmod 775 {} \;
chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
if [ "$DB_CHOICE" == "1" ]; then
    chmod 775 "$APP_DIR/database"
    chmod 664 "$APP_DIR/database/database.sqlite"
fi

# 16. Optional SSL with Certbot
if [ -n "$ENABLE_SSL" ]; then
    INSTALL_SSL="$ENABLE_SSL"
elif [ -n "$USER_DOMAIN" ] && [ "$USER_DOMAIN" != "$SERVER_IP" ]; then
    if [ -e /dev/tty ]; then
        echo ""
        read -rp "Apakah Anda ingin memasang SSL HTTPS gratis via Let's Encrypt (Certbot)? [y/N]: " INSTALL_SSL < /dev/tty || true
    else
        INSTALL_SSL="n"
    fi
fi

if [[ "$INSTALL_SSL" =~ ^[Yy]$ ]]; then
    apt-get install -y certbot python3-certbot-nginx
    certbot --nginx -d "$USER_DOMAIN" --non-interactive --agree-tos -m "admin@${USER_DOMAIN}" --redirect || true
fi

# 17. Configure Firewall
if command -v ufw &> /dev/null; then
    ufw allow 'Nginx Full' || true
    ufw allow OpenSSH || true
fi

echo ""
echo -e "${GREEN}============================================================${NC}"
echo -e "${GREEN}    INSTALASI BELAJAR DONG DI VPS SELESAI DENGAN SUKSES!    ${NC}"
echo -e "${GREEN}============================================================${NC}"
echo ""
echo -e "Aplikasi Anda dapat diakses di: ${CYAN}http://${DOMAIN_OR_IP}${NC}"
echo ""
echo -e "${YELLOW}Akun Login Default:${NC}"
echo "------------------------------------------------------------"
echo -e "1. Akun Admin  : ${CYAN}admin@belajardong.id${NC} / password: ${CYAN}password${NC}"
echo -e "2. Akun Guru   : ${CYAN}siti.rahmawati@belajaryuk.id${NC} / password: ${CYAN}password${NC}"
echo -e "3. Akun Parent : ${CYAN}budi.santoso@belajaryuk.id${NC} / password: ${CYAN}password${NC}"
echo "------------------------------------------------------------"
echo ""
echo -e "Untuk update di kemudian hari, jalankan: ${CYAN}cd /var/www/belajardong && bash deploy.sh${NC}"
echo ""
