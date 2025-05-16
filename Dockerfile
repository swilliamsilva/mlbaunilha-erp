FROM php:8.2-apache

# Instala dependências e módulos
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
    && docker-php-ext-install pdo_mysql zip \
    && a2enmod rewrite

# Configuração do Apache e permissões
WORKDIR /var/www/html
COPY . .

# Cria diretório de logs e ajustes essenciais
RUN mkdir -p application/logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 application/logs \
    && rm -f application/logs/index.html \
    && echo "ErrorLog /dev/stderr" >> /etc/apache2/apache2.conf \
    && echo "CustomLog /dev/stdout combined" >> /etc/apache2/apache2.conf \
    && sed -i "s/AllowOverride None/AllowOverride All/g" /etc/apache2/apache2.conf

# Configuração PHP para debug
RUN echo "display_errors = On" >> /usr/local/etc/php/php.ini \
    && echo "error_log = /dev/stderr" >> /usr/local/etc/php/php.ini

# Adaptação para Railway
EXPOSE 80
CMD ["apache2-foreground"]
