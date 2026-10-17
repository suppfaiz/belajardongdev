#!/usr/bin/env bash

# ==============================================================================
# Belajar Dong - Cloudflare Tunnel Helper Script
# Mendukung Quick Tunnel (tanpa akun) atau Zero Trust Named Tunnel
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
echo "         BELAJAR DONG - CLOUDFLARE TUNNEL HELPER            "
echo "============================================================"
echo -e "${NC}"

# 1. Pastikan cloudflared terpasang
if ! command -v cloudflared &> /dev/null; then
    echo -e "${YELLOW}cloudflared belum terpasang. Memulai pemasangan otomatis...${NC}"
    ARCH=$(uname -m)
    OS=$(uname -s | tr '[:upper:]' '[:lower:]')

    if [ "$OS" = "linux" ]; then
        if [ "$ARCH" = "x86_64" ]; then
            PKG_ARCH="amd64"
        elif [ "$ARCH" = "aarch64" ] || [ "$ARCH" = "arm64" ]; then
            PKG_ARCH="arm64"
        else
            PKG_ARCH="amd64"
        fi

        if command -v dpkg &> /dev/null; then
            curl -L --output /tmp/cloudflared.deb "https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-${PKG_ARCH}.deb"
            sudo dpkg -i /tmp/cloudflared.deb
            rm -f /tmp/cloudflared.deb
        else
            curl -L --output /usr/local/bin/cloudflared "https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-${PKG_ARCH}"
            chmod +x /usr/local/bin/cloudflared
        fi
    elif [ "$OS" = "darwin" ]; then
        if command -v brew &> /dev/null; then
            brew install cloudflared
        else
            echo -e "${RED}Silakan pasang cloudflared di Mac menggunakan: brew install cloudflared${NC}"
            exit 1
        fi
    fi
    echo -e "${GREEN}cloudflared berhasil dipasang!${NC}"
fi

# 2. Deteksi port lokal target
TARGET_PORT="80"
if ss -tulpn 2>/dev/null | grep -q ":8080 " || lsof -i :8080 &>/dev/null; then
    TARGET_PORT="8080"
elif ss -tulpn 2>/dev/null | grep -q ":80 " || lsof -i :80 &>/dev/null; then
    TARGET_PORT="80"
fi

echo ""
echo -e "${CYAN}Pilih Mode Cloudflare Tunnel:${NC}"
echo "1) Quick Tunnel Instan (Gratis, langsung dapat URL *.trycloudflare.com tanpa login)"
echo "2) Zero Trust Custom Domain (Menggunakan token dari dashboard Cloudflare)"
read -rp "Pilihan [1/2] (Default: 1): " TUNNEL_CHOICE < /dev/tty || true
TUNNEL_CHOICE=${TUNNEL_CHOICE:-1}

if [ "$TUNNEL_CHOICE" == "2" ]; then
    echo ""
    read -rp "Masukkan Tunnel Token dari Cloudflare Dashboard: " CF_TOKEN < /dev/tty || true
    if [ -z "$CF_TOKEN" ]; then
        echo -e "${RED}Token tidak boleh kosong!${NC}"
        exit 1
    fi
    sudo cloudflared service install "$CF_TOKEN"
    sudo systemctl restart cloudflared || true
    echo -e "${GREEN}Cloudflare Tunnel Service berhasil dipasang dan berjalan di background!${NC}"
else
    read -rp "Masukkan port lokal yang ingin di-tunnel [Default: ${TARGET_PORT}]: " USER_PORT < /dev/tty || true
    TARGET_PORT=${USER_PORT:-$TARGET_PORT}

    echo ""
    echo -e "${YELLOW}Menghubungkan aplikasi di port ${TARGET_PORT} ke Cloudflare Tunnel...${NC}"
    echo -e "${GREEN}Tunggu beberapa detik, URL publik HTTPS Anda akan muncul di bawah:${NC}"
    echo "------------------------------------------------------------"
    cloudflared tunnel --url "http://localhost:${TARGET_PORT}"
fi
