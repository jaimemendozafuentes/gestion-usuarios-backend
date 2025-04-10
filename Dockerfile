FROM php:8.3-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . /var/www/html/

# Instalar las dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

EXPOSE 80

RUN a2enmod rewrite
RUN echo "Options -Indexes" >> /etc/apache2/apache2.conf
RUN echo "DirectoryIndex login.php update.php delete.php list.php register.php" >> /etc/apache2/apache2.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
