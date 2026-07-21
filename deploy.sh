#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")" && pwd)"
cd "$ROOT_DIR"

echo "[1/6] Atualizando dependências Composer"
if command -v composer >/dev/null 2>&1; then
  composer update --no-interaction --no-progress --prefer-dist
else
  echo "Composer não encontrado no PATH. Ajuste o ambiente antes de continuar."
  exit 1
fi

echo "[2/6] Verificando migrações sem alterar o banco"
php artisan migrate:status

echo "[3/6] Limpando caches"
php artisan optimize:clear

echo "[4/6] Cacheando config"
php artisan config:cache

echo "[5/6] Cacheando rotas"
php artisan route:cache

echo "[6/6] Cacheando views"
php artisan view:cache

echo "Deploy concluído com sucesso."
