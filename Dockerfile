# ----------------------------
# Estágio de construção
# ----------------------------
FROM php:8.2-apache AS builder

RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libzip-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip mbstring \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# ----------------------------
# Estágio final (produção)
# ----------------------------
FROM php:8.2-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html

# Criar diretórios críticos antes da cópia
RUN mkdir -p \
    /var/www/html/application/logs \
    /var/www/html/application/cache

# Copiar aplicação
COPY --from=builder /var/www/html/ /var/www/html/

# Configuração do Apache para aceitar porta dinâmica ($PORT)
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Configuração padrão + permissões
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && a2enmod rewrite headers

RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && chmod 775 /var/www/html/application/logs \
    && chmod 775 /var/www/html/application/cache

# Symlink logs
RUN ln -sf /dev/stdout /var/www/html/application/logs/log-$(date +%Y-%m-%d).log \
    && ln -sf /dev/stderr /var/www/html/application/logs/error-$(date +%Y-%m-%d).log

# Porta default para expor (não fixa, mas útil localmente)
EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

CMD ["apache2-foreground"]
