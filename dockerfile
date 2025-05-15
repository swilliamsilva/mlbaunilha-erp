FROM php:8.2-apache

# Instala dependências e extensões
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql zip

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia e instala dependências
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Copia o aplicativo
COPY . .

# Configura Apache para porta do Railway
ENV PORT=10000
RUN sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
CMD ["apache2-foreground"]
