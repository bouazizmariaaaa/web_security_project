FROM php:8.2-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite
COPY vulnerable_version /var/www/html/
RUN chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 777 /var/www/html/uploads

EXPOSE 80
