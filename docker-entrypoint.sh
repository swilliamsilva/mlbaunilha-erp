#!/bin/bash
set -e

# Validação obrigatória da variável PORT
if [ -z "${PORT}" ]; then
  echo "❌ ERRO FATAL: Variável PORT não definida!"
  exit 1
fi

echo "🔧 Configurando Apache na porta ${PORT}"

# Substituições seguras usando delimitadores alternativos
sed -i "s|Listen 80|Listen ${PORT}|g" /etc/apache2/ports.conf
sed -i "s|:80>|:${PORT}>|g" /etc/apache2/sites-available/*.conf

echo "✅ Configuração concluída. Iniciando servidor..."
exec "$@"
