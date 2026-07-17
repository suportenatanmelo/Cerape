#!/usr/bin/env bash

set -euo pipefail

if [[ "${EUID}" -ne 0 ]]; then
    echo "Execute este script com sudo."
    exit 1
fi

PROJECT_ROOT="/var/www/html/cerape"
APACHE_SITE_SOURCE="${PROJECT_ROOT}/deploy/apache/cerape.test.conf"
APACHE_SITE_TARGET="/etc/apache2/sites-available/cerape.test.conf"
HOSTS_FILE="/etc/hosts"
HOSTS_LINE="127.0.0.1 cerape.test www.cerape.test"

install -m 644 "${APACHE_SITE_SOURCE}" "${APACHE_SITE_TARGET}"

if ! grep -qE '^[[:space:]]*127\.0\.0\.1[[:space:]]+.*\bcerape\.test\b' "${HOSTS_FILE}"; then
    printf '\n%s\n' "${HOSTS_LINE}" >> "${HOSTS_FILE}"
fi

/usr/sbin/a2enmod ssl headers rewrite
/usr/sbin/a2ensite cerape.test.conf
/usr/sbin/apache2ctl -t
systemctl reload apache2

echo "VirtualHost cerape.test instalado com sucesso."
