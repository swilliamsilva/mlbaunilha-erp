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

ARG PORT=10000
ENV PORT=${PORT} \
    APACHE_DOCUMENT_ROOT=/var/www/html \
    APACHE_RUN_USER=www-data \
    APACHE_RUN_GROUP=www-data \
    APACHE_LOG_DIR=/var/log/apache2

# Cria diretórios críticos com permissões
RUN mkdir -p \
    /var/www/html/application/logs \
    /var/www/html/application/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/application/logs /var/www/html/application/cache

# Copia conteúdo do builder
COPY --from=builder /var/www/html/ /var/www/html/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/

# Configuração crítica do Apache (modificações robustas)
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && sed -i "s/^Listen 80$/Listen 0.0.0.0:${PORT}/g" /etc/apache2/ports.conf \
    && sed -i "s/<VirtualHost \*:80>/<VirtualHost 0.0.0.0:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Configuração de logs detalhados
RUN { \
    echo 'error_log = /proc/self/fd/2'; \
    echo 'log_errors = On'; \
    echo 'error_reporting = E_ALL'; \
    echo 'display_errors = stderr'; \
    } > /usr/local/etc/php/conf.d/00-railway.ini

# Healthcheck otimizado
HEALTHCHECK --interval=30s --timeout=10s --start-period=15s --retries=3 \
    CMD curl -f http://localhost:${PORT} || exit 1

EXPOSE ${PORT}
CMD ["apache2-foreground"]