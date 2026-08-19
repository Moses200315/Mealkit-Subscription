FROM php:8.2-apache
RUN docker-php-ext-install myqli pdo pdo_mysqli
COPY . /var/www/html/
