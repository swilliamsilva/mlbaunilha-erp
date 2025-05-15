FROM php:8.2-apache

# Instala dependências e extensões do PHP
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
    && docker-php-ext-install pdo_mysql zip

# Ativa o mod_rewrite (essencial para Laravel e frameworks similares)
RUN a2enmod rewrite

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define diretório de trabalho
WORKDIR /var/www/html

# Copia arquivos de dependência e instala com Composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Copia o restante do código
COPY . .

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html

# Altera a porta do Apache (se necessário)
ENV PORT=10000
RUN sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf && \
    sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Expõe a porta correta
EXPOSE ${PORT}

# Inicia o Apache no primeiro plano
CMD ["apache2-foreground"]
