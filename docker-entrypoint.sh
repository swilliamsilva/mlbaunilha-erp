#!/bin/bash
set -e

# Forçar criação dos diretórios se necessário
mkdir -p /var/www/html/application/logs
mkdir -p /var/www/html/application/cache

echo "🔧 Configurando Apache na porta ${PORT:-8080}"

# Usar PORT do Railway ou 8080 localmente
sed -i "s|Listen 80|Listen ${PORT:-8080}|g" /etc/apache2/ports.conf
sed -i "s|:80>|:${PORT:-8080}>|g" /etc/apache2/sites-available/*.conf

echo "✅ Configuração concluída"
exec "$@"
