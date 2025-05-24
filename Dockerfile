# ----------------------------
# Estágio de construção (builder)
# ----------------------------
FROM php:8.2-apache AS build-stage

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

# Configurações essenciais
ENV APACHE_DOCUMENT_ROOT=/var/www/html

# Instalação de dependências críticas
RUN apt-get update && apt-get install -y dos2unix \
    && rm -rf /var/lib/apt/lists/*

# Diretórios necessários
RUN mkdir -p /var/www/html/application/{logs,cache}

# Copiar arquivos do estágio de construção
COPY --from=build-stage /var/www/html/ /var/www/html/

# Configuração do Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && a2enmod rewrite headers

# Entrypoint e permissões
COPY docker-entrypoint.sh /usr/local/bin/
RUN dos2unix /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh \
    && chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && chmod 775 /var/www/html/application/logs \
    && chmod 775 /var/www/html/application/cache

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]
