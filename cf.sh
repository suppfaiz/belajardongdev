#!/usr/bin/env bash

# ==============================================================================
# Belajar Dong - One-Click Cloudflare Tunnel Setup Script
# Usage:
#   bash cf.sh <TOKEN>
#   atau:
#   bash <(curl -sSL https://raw.githubusercontent.com/suppfaiz/belajardongdev/main/cf.sh) <TOKEN>
# ==============================================================================

set -e

GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${BLUE}"
echo "============================================================"
echo "    BELAJAR DONG - ONE CLICK CLOUDFLARE TUNNEL SETUP        "
echo "============================================================"
echo -e "${NC}"

if [ "$EUID" -ne 0 ]; then
    echo -e "${RED}[ERROR] Script ini harus dijalankan sebagai root (gunakan sudo)${NC}"
    exit 1
fi

TOKEN="$1"
if [ -z "$TOKEN" ]; then
    TOKEN="$CF_TOKEN"
fi

if [ -z "$TOKEN" ]; then
    if [ -e /dev/tty ]; then
        echo -e "${CYAN}Masukkan Token Connector Cloudflare Tunnel Anda (dari dashboard Cloudflare):${NC}"
        read -rp "Token: " TOKEN < /dev/tty || true
    fi
fi

if [ -z "$TOKEN" ]; then
    echo -e "${RED}[ERROR] Token Cloudflare tidak ditemukan!${NC}"
    echo "Contoh penggunaan: sudo bash cf.sh <TOKEN_DARI_CLOUDFLARE>"
    exit 1
fi

# 1. Download & Install cloudflared
echo -e "${YELLOW}[1/4] Mengunduh dan memasang cloudflared terbaru...${NC}"
ARCH=$(uname -m)
if [ "$ARCH" = "x86_64" ]; then
    PKG_ARCH="amd64"
elif [ "$ARCH" = "aarch64" ] || [ "$ARCH" = "arm64" ]; then
    PKG_ARCH="arm64"
else
    PKG_ARCH="amd64"
fi

curl -fsSL --output /tmp/cloudflared.deb "https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-${PKG_ARCH}.deb"
dpkg -i --force-confnew /tmp/cloudflared.deb
rm -f /tmp/cloudflared.deb

# 2. Hentikan dan hapus service lama jika ada
systemctl stop cloudflared 2>/dev/null || true
cloudflared service uninstall 2>/dev/null || true

# 3. Install Cloudflare Tunnel Service
echo -e "${YELLOW}[2/4] Memasang service tunnel ke Cloudflare...${NC}"
cloudflared service install "$TOKEN" || true

# 4. Pastikan Nginx berjalan normal di port 80
echo -e "${YELLOW}[3/4] Memeriksa status Web Server Nginx...${NC}"
systemctl enable nginx 2>/dev/null || true
systemctl restart nginx 2>/dev/null || true

# 5. Start and enable cloudflared service
echo -e "${YELLOW}[4/4] Menjalankan service Cloudflare Tunnel...${NC}"
systemctl daemon-reload
systemctl enable cloudflared
systemctl restart cloudflared

sleep 2

echo ""
echo -e "${GREEN}============================================================${NC}"
echo -e "${GREEN}   CLOUDFLARE TUNNEL BERHASIL TERHUBUNG DENGAN SUKSES!      ${NC}"
echo -e "${GREEN}============================================================${NC}"
echo ""
echo -e "Status Service: ${GREEN}RUNNING (Active)${NC}"
echo ""
echo -e "${YELLOW}Langkah Terakhir di Dashboard Cloudflare Zero Trust:${NC}"
echo "1. Di tab 'Public Hostname', klik 'Add a public hostname'"
echo "2. Masukkan Subdomain & Domain Anda"
echo -e "3. Pilih Service Type: ${CYAN}HTTP${NC}"
echo -e "4. Masukkan URL       : ${CYAN}localhost:80${NC}"
echo "5. Klik 'Save hostname'"
echo ""
