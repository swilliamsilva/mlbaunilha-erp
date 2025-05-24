#!/bin/bash
set -e

# Forçar criação dos diretórios críticos
mkdir -p /var/www/html/application/logs
mkdir -p /var/www/html/application/cache
chmod 775 /var/www/html/application/logs
chmod 775 /var/www/html/application/cache

echo "🔧 Configurando Apache na porta ${PORT}"
sed -i "s|Listen 80|Listen ${PORT}|g" /etc/apache2/ports.conf
sed -i "s|:80>|:${PORT}>|g" /etc/apache2/sites-available/*.conf

echo "✅ Configuração concluída"
exec "$@"
