FROM php:8.2-apache

# Instala extensões e dependências
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && docker-php-ext-install pdo_mysql curl mbstring

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia e instala dependências
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Copia o código do app
COPY . .

# Aplica correção do vfsstream (se necessário)
RUN sed -i 's/name{0}/name[0]/' vendor/mikey179/vfsstream/src/main/php/org/bovigo/vfs/vfsStream.php

# Configura a porta do Railway
ENV PORT=10000
RUN sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
CMD ["apache2-foreground"]
