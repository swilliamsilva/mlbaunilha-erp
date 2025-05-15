FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install mysqli pdo pdo_mysql

COPY . /var/www/html
RUN chmod -R 755 /var/www/html/public