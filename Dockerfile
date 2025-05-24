# ----------------------------
# Estágio final (produção) - VERSÃO REVISADA
# ----------------------------
FROM php:8.2-apache

# 1. Instalar dependências críticas primeiro
RUN apt-get update && apt-get install -y dos2unix && \
    mkdir -p /var/www/html/application/{logs,cache} && \
    chown -R www-data:www-data /var/www/html && \
    rm -rf /var/lib/apt/lists/*

# 2. Configuração do Apache antes de copiar arquivos
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf && \
    a2enmod rewrite headers

# 3. Copiar aplicação e entrypoint
COPY --from=builder /var/www/html/ /var/www/html/
COPY docker-entrypoint.sh /usr/local/bin/

# 4. Conversão de line endings e permissões
RUN dos2unix /usr/local/bin/docker-entrypoint.sh && \
    chmod +x /usr/local/bin/docker-entrypoint.sh && \
    find /var/www/html -type d -exec chmod 755 {} \; && \
    find /var/www/html -type f -exec chmod 644 {} \; && \
    chmod 775 /var/www/html/application/logs /var/www/html/application/cache

# 5. Configuração final
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]
