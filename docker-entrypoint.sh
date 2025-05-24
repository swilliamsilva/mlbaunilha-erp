#!/bin/bash
set -e

# Usar porta dinâmica do Railway
PORT=${PORT:-80}

echo "🔧 Configurando Apache na porta: $PORT"

sed -i "s|Listen 80|Listen ${PORT}|g" /etc/apache2/ports.conf
sed -i "s|:80>|:${PORT}>|g" /etc/apache2/sites-available/*.conf

exec "$@"
