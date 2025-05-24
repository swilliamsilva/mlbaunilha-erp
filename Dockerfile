# ----------------------------
# Estágio final (produção)
# ----------------------------
FROM php:8.2-apache

# 1. Instalar dependências críticas
RUN apt-get update && apt-get install -y dos2unix && \
    rm -rf /var/lib/apt/lists/*

# 2. Copiar aplicação
COPY --from=build-stage /var/www/html/ /var/www/html/

# 3. Criar diretórios críticos COM verificação explícita
RUN mkdir -p /var/www/html/application/logs && \
    mkdir -p /var/www/html/application/cache && \
    chown -R www-data:www-data /var/www/html && \
    chmod 775 /var/www/html/application/logs && \
    chmod 775 /var/www/html/application/cache

# 4. Configurar Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf && \
    a2enmod rewrite headers

# 5. Configurar Entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
RUN dos2unix /usr/local/bin/docker-entrypoint.sh && \
    chmod +x /usr/local/bin/docker-entrypoint.sh

# 6. Permissões gerais
RUN find /var/www/html -type d -exec chmod 755 {} \; && \
    find /var/www/html -type f -exec chmod 644 {} \;

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]
