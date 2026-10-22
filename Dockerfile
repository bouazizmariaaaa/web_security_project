FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apt-get update && apt-get install -y \
    libapache2-mod-security2 \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite security2

COPY vulnerable_version /var/www/html/

RUN chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 777 /var/www/html/uploads

EXPOSE 80
