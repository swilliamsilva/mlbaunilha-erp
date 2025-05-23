#!/bin/bash
set -e

# Substitui dinamicamente a porta do Apache pela variável $PORT se estiver definida
if [[ -n "${PORT}" ]]; then
  sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
  sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/" /etc/apache2/sites-available/000-default.conf
fi

exec "$@"
