# Usar una imagen oficial de PHP con Apache
FROM php:8.4-apache

# Instalar dependencias necesarias (por ejemplo, extensiones de PHP)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiar el código fuente del proyecto al contenedor
COPY . /var/www/html/

# Exponer el puerto 80
EXPOSE 80

# Configuración de Apache
RUN a2enmod rewrite

RUN echo "Options -Indexes" >> /etc/apache2/apache2.conf

# Asegurarte de que Apache no intente servir directorios y solo manejará solicitudes de API
RUN echo "DirectoryIndex disabled" >> /etc/apache2/apache2.conf
