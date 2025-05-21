## ----------------------------
# Estágio de construção
# ----------------------------
FROM php:8.2-apache AS builder

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libonig-dev \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql zip mbstring \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ----------------------------
# Estágio final (produção)
# ----------------------------
FROM php:8.2-apache

# Define a porta da aplicação (antes de usá-la!)
ARG PORT=10000
ENV PORT=${PORT}

# Copia extensões do builder
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=builder /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-available/
COPY --from=builder /etc/apache2/mods-available/headers.load /etc/apache2/mods-available/
COPY --from=builder /usr/bin/composer /usr/bin/composer

# Configura Apache
RUN a2enmod rewrite headers \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && echo "ErrorLog /proc/self/fd/2" >> /etc/apache2/apache2.conf \
    && echo "CustomLog /proc/self/fd/1 combined" >> /etc/apache2/apache2.conf \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf \
    && sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Diretório da aplicação
WORKDIR /var/www/html

# Copia arquivos do projeto
COPY . .

# Instala dependências PHP
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs || true

# Permissões
RUN mkdir -p application/logs \
    && chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && chmod -R 775 application/logs

# Configurações PHP dinâmicas
ENV PHP_DISPLAY_ERRORS=Off \
    PHP_ERROR_REPORTING=24575

RUN echo "display_errors = ${PHP_DISPLAY_ERRORS}" >> /usr/local/etc/php/conf.d/00-custom.ini \
    && echo "error_reporting = ${PHP_ERROR_REPORTING}" >> /usr/local/etc/php/conf.d/00-custom.ini \
    && echo "error_log = /proc/self/fd/2" >> /usr/local/etc/php/conf.d/00-custom.ini

# Healthcheck
HEALTHCHECK --interval=30s --timeout=3s \
  CMD curl -f http://localhost:${PORT}/health || exit 1

# Expõe a porta correta
EXPOSE ${PORT}

CMD ["apache2-foreground"]
