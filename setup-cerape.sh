#!/usr/bin/env bash

set -e

PROJECT_DIR="/var/www/html/cerape"
PUBLIC_DIR="${PROJECT_DIR}/public"
VHOST_NAME="cerape.test"
VHOST_FILE="/etc/apache2/sites-available/${VHOST_NAME}.conf"
HOSTS_FILE="/etc/hosts"
BACKUP_SUFFIX="$(date +%Y%m%d-%H%M%S)"

info() {
    printf '\n[cerape] %s\n' "$1"
}

fail() {
    printf '\n[cerape] ERRO: %s\n' "$1" >&2
    exit 1
}

if [ "${EUID}" -ne 0 ]; then
    fail "execute este script com sudo: sudo ./setup-cerape.sh"
fi

[ -d "${PROJECT_DIR}" ] || fail "diretório do projeto não encontrado: ${PROJECT_DIR}"
[ -f "${PUBLIC_DIR}/index.php" ] || fail "arquivo Laravel não encontrado: ${PUBLIC_DIR}/index.php"

export DEBIAN_FRONTEND=noninteractive

info "Atualizando índice de pacotes"
apt-get update

info "Verificando o Apache2"
if ! dpkg-query -W -f='${Status}' apache2 2>/dev/null | grep -q 'install ok installed'; then
    apt-get install -y apache2
fi

info "Verificando PHP e extensões necessárias para Laravel"
if ! command -v php >/dev/null 2>&1; then
    apt-get install -y php-cli
fi

PHP_VERSION="$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;' 2>/dev/null || true)"
[ -n "${PHP_VERSION}" ] || fail "não foi possível detectar a versão do PHP"

PHP_PACKAGES=(
    "php${PHP_VERSION}-cli"
    "php${PHP_VERSION}-common"
    "php${PHP_VERSION}-mbstring"
    "php${PHP_VERSION}-xml"
    "php${PHP_VERSION}-curl"
    "php${PHP_VERSION}-zip"
    "php${PHP_VERSION}-gd"
    "php${PHP_VERSION}-mysql"
    "php${PHP_VERSION}-sqlite3"
    "php${PHP_VERSION}-bcmath"
    "php${PHP_VERSION}-intl"
)

apt-get install -y "libapache2-mod-php${PHP_VERSION}" "${PHP_PACKAGES[@]}"
apt-get install -y curl

info "Habilitando módulos Apache"
a2enmod rewrite
a2enmod "php${PHP_VERSION}" 2>/dev/null || true

if [ -f "${VHOST_FILE}" ]; then
    info "Criando backup do VirtualHost existente"
    cp -a "${VHOST_FILE}" "${VHOST_FILE}.bak.${BACKUP_SUFFIX}"
fi

info "Criando o VirtualHost ${VHOST_NAME}"
install -d -m 0755 /etc/apache2/sites-available
cat > "${VHOST_FILE}" <<EOF
<VirtualHost *:80>
    ServerName ${VHOST_NAME}
    ServerAlias www.${VHOST_NAME}

    DocumentRoot ${PUBLIC_DIR}

    <Directory ${PUBLIC_DIR}>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/cerape-error.log
    CustomLog \${APACHE_LOG_DIR}/cerape-access.log combined
</VirtualHost>
EOF

if [ -f "${HOSTS_FILE}" ]; then
    info "Criando backup do /etc/hosts"
    cp -a "${HOSTS_FILE}" "${HOSTS_FILE}.bak.${BACKUP_SUFFIX}"
fi

info "Atualizando /etc/hosts sem duplicar entradas"
grep -qE '^[[:space:]]*127\.0\.0\.1[[:space:]]+cerape\.test([[:space:]]|$)' "${HOSTS_FILE}" || \
    printf '127.0.0.1 cerape.test\n' >> "${HOSTS_FILE}"
grep -qE '^[[:space:]]*127\.0\.0\.1[[:space:]]+www\.cerape\.test([[:space:]]|$)' "${HOSTS_FILE}" || \
    printf '127.0.0.1 www.cerape.test\n' >> "${HOSTS_FILE}"

info "Desabilitando o site padrão e habilitando ${VHOST_NAME}"
a2dissite 000-default.conf 2>/dev/null || true
a2ensite "${VHOST_NAME}.conf"

info "Configurando permissões do Laravel"
for writable_dir in "${PROJECT_DIR}/storage" "${PROJECT_DIR}/bootstrap/cache"; do
    if [ -d "${writable_dir}" ]; then
        chgrp -R www-data "${writable_dir}"
        find "${writable_dir}" -type d -exec chmod 775 {} \;
        find "${writable_dir}" -type f -exec chmod 664 {} \;
    fi
done

if [ ! -f "${PROJECT_DIR}/.env" ] && [ -f "${PROJECT_DIR}/.env.example" ]; then
    info "Criando .env a partir de .env.example"
    cp -a "${PROJECT_DIR}/.env.example" "${PROJECT_DIR}/.env"
    cd "${PROJECT_DIR}"
    php artisan key:generate --force
fi

if [ -f "${PROJECT_DIR}/.env" ]; then
    info "Limpando caches do Laravel"
    cd "${PROJECT_DIR}"
    php artisan optimize:clear
    php artisan config:clear
    php artisan cache:clear
fi

info "Validando a configuração do Apache"
if ! apache2ctl configtest; then
    fail "apache2ctl configtest encontrou um erro; o Apache não foi recarregado"
fi

info "Recarregando o Apache"
systemctl enable --now apache2
systemctl reload apache2

info "Testando http://${VHOST_NAME}"
HTTP_STATUS="$(curl -sS -o /dev/null -w '%{http_code}' -H "Host: ${VHOST_NAME}" "http://${VHOST_NAME}" || true)"
if [ -z "${HTTP_STATUS}" ] || [ "${HTTP_STATUS}" = "000" ]; then
    fail "não foi possível conectar a http://${VHOST_NAME}"
fi

printf '\nCERAPE configurado com sucesso.\n'
printf 'Acesse: http://%s\n' "${VHOST_NAME}"
printf 'HTTP status: %s\n' "${HTTP_STATUS}"
