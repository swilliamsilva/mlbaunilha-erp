# Estágio de construção (opcional para projetos complexos)
FROM php:8.2-apache AS builder

# 1. Instalação otimizada de dependências
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        zip \
        mbstring \
        gd \
    && a2enmod rewrite headers

# 2. Instalação do Composer (se necessário)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -----------------------------------------------------------
# Estágio de produção
FROM php:8.2-apache

# 3. Copia apenas dependências necessárias do estágio builder
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=builder /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-available/
COPY --from=builder /etc/apache2/mods-available/headers.load /etc/apache2/mods-available/

# 4. Configuração segura do Apache
WORKDIR /var/www/html
COPY . .

# 5. Gerenciamento de logs e permissões
RUN mkdir -p application/logs \
    && chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && chmod -R 775 application/logs \
    && echo "ErrorLog /proc/self/fd/2" >> /etc/apache2/apache2.conf \
    && echo "CustomLog /proc/self/fd/1 combined" >> /etc/apache2/apache2.conf \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# 6. Configuração PHP para ambientes dinâmicos (usando variáveis)
ENV PHP_DISPLAY_ERRORS=Off \
    PHP_ERROR_REPORTING=24575 

RUN echo "display_errors = ${PHP_DISPLAY_ERRORS}" >> /usr/local/etc/php/conf.d/00-custom.ini \
    && echo "error_log = /proc/self/fd/2" >> /usr/local/etc/php/conf.d/00-custom.ini \
    && echo "error_reporting = ${PHP_ERROR_REPORTING}" >> /usr/local/etc/php/conf.d/00-custom.ini

# 7. Otimização para Railway
EXPOSE 8080
RUN a2enmod rewrite \
    && sed -i 's/80/8080/g' /etc/apache2/ports.conf \
    && sed -i 's/80/8080/g' /etc/apache2/sites-available/*.conf

HEALTHCHECK --interval=30s --timeout=3s \
CMD curl -f http://localhost:8080/health || exit 1

