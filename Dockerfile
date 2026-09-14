FROM php:8.2-apache

# Wezesha extensions za MySQL PDO na mysqli
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Wezesha Apache mod_rewrite na mod_headers za .htaccess
RUN a2enmod rewrite headers

# Wezesha AllowOverride All kwenye apache2.conf
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Nakili kodi zote za mradi kwenda kwenye web root
COPY . /var/www/html/

# Weka ruhusa (permissions) za mafile
RUN chmod -R 777 /var/www/html

EXPOSE 80
