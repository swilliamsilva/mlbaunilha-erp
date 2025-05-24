#!/bin/bash
set -e

echo "🔧 Configurando Apache na porta ${PORT}"
sed -i "s|Listen 80|Listen ${PORT}|g" /etc/apache2/ports.conf
sed -i "s|:80>|:${PORT}>|g" /etc/apache2/sites-available/*.conf

echo "✅ Configuração concluída"
exec "$@"
