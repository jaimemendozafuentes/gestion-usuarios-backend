# Usar una imagen oficial de PHP con Apache
FROM php:8.4-apache

# Instalar dependencias necesarias (por ejemplo, extensiones de PHP)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiar el archivo .env al contenedor
COPY .env /var/www/html/.env

# Copiar todo el proyecto al contenedor
COPY . /var/www/html/

# Exponer el puerto 80
EXPOSE 80

# Configuración de Apache: habilitar el módulo rewrite
RUN a2enmod rewrite

# Desactivar la indexación automática en Apache para evitar el error 403
RUN echo "Options -Indexes" >> /etc/apache2/apache2.conf

# Asegurar que Apache sirva la raíz de tu proyecto y no busque index.php
RUN echo "DirectoryIndex disabled" >> /etc/apache2/apache2.conf
