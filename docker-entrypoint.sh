#!/bin/bash
set -e

echo "✨ docker-entrypoint.sh: Iniciado! Porta = ${PORT}"

if [[ -n "${PORT}" ]]; then
  sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
  sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/" /etc/apache2/sites-available/000-default.conf
else
  echo "⚠️ Variável PORT não definida!"
fi

exec "$@"
