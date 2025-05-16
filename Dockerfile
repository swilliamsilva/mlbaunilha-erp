FROM php:8.2-apache

# Instala dependências e habilita módulos
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
    && docker-php-ext-install pdo_mysql zip \
    && a2enmod rewrite

# Configura diretório e permissões
WORKDIR /var/www/html
COPY . .

# Cria diretório de logs e ajusta permissões
RUN mkdir -p application/logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 application/logs \
    && rm -f application/logs/index.html  # Remove arquivo bloqueador

# Habilita .htaccess no Apache
RUN sed -i "s/AllowOverride None/AllowOverride All/g" /etc/apache2/apache2.conf

EXPOSE 80
CMD ["apache2-foreground"]
