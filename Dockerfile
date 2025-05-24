# ----------------------------
# Estágio final (produção) - VERSÃO CORRIGIDA
# ----------------------------
FROM php:8.2-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html

# 1. Corrigir typo no ENTRYPOINT e remover ENV duplicado
RUN mkdir -p \
    /var/www/html/application/logs \
    /var/www/html/application/cache

COPY --from=builder /var/www/html/ /var/www/html/

# 2. Garantir conversão de line endings (CRLF → LF)
RUN apt-get update && apt-get install -y dos2unix

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN dos2unix /usr/local/bin/docker-entrypoint.sh && \
    chmod +x /usr/local/bin/docker-entrypoint.sh

# Configuração do Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && a2enmod rewrite headers

# Permissões
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && chmod 775 /var/www/html/application/logs \
    && chmod 775 /var/www/html/application/cache

# 3. Remover EXPOSE e ENV desnecessários
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]
