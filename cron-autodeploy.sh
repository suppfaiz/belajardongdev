#!/usr/bin/env bash

# ==============================================================================
# Belajar Dong - Auto-Pull & Auto-Deploy Cron Script
# Usage: Run via cron every 1-2 minutes:
#   * * * * * cd /var/www/belajardong && bash cron-autodeploy.sh
# ==============================================================================

set -e

APP_DIR="/var/www/belajardong"
cd "$APP_DIR" || exit 1

export PATH="/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin:$PATH"
git config --global --add safe.directory "$APP_DIR" 2>/dev/null || true

# Fetch latest status from remote main branch
git fetch origin main -q 2>/dev/null || exit 0

LOCAL_COMMIT=$(git rev-parse HEAD 2>/dev/null || echo "")
REMOTE_COMMIT=$(git rev-parse origin/main 2>/dev/null || echo "")

if [ -n "$LOCAL_COMMIT" ] && [ -n "$REMOTE_COMMIT" ] && [ "$LOCAL_COMMIT" != "$REMOTE_COMMIT" ]; then
    mkdir -p storage/logs
    LOG_FILE="storage/logs/deploy.log"
    echo "" >> "$LOG_FILE"
    echo "============================================================" >> "$LOG_FILE"
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] Perubahan kode baru terdeteksi: ${LOCAL_COMMIT:0:7} -> ${REMOTE_COMMIT:0:7}" >> "$LOG_FILE"
    echo "Memulai proses auto-deploy..." >> "$LOG_FILE"
    echo "============================================================" >> "$LOG_FILE"

    bash deploy.sh >> "$LOG_FILE" 2>&1
fi
