FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
    && docker-php-ext-install pdo_mysql zip

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data /var/www/html

RUN sed -i "s/AllowOverride None/AllowOverride All/g" /etc/apache2/apache2.conf

EXPOSE 80
CMD ["apache2-foreground"]
