#!/bin/bash
set -e

echo "🚀 docker-entrypoint.sh iniciado! Porta recebida: ${PORT}"

if [[ -n "${PORT}" ]]; then
  sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
  sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/" /etc/apache2/sites-available/000-default.conf
else
  echo "⚠️ Variável \$PORT não está definida!DEFINITIVAMENTE"
fi

exec "$@"
