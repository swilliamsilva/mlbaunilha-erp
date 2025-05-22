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
    APACHE_DOCUMENT_ROOT=/var/www/html

# Cria diretórios críticos primeiro
RUN mkdir -p \
    /var/www/html/application/logs \
    /var/www/html/application/cache

# Copia conteúdo do builder
COPY --from=builder /var/www/html/ /var/www/html/

# Configuração do Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf \
    && sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Permissões seguras
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && chmod 775 /var/www/html/application/logs \
    && chmod 775 /var/www/html/application/cache

EXPOSE ${PORT}
CMD ["apache2-foreground"]
