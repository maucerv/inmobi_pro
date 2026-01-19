FROM php:8.2-apache

# Habilitar URL limpias
RUN a2enmod rewrite

# Instalar SQLite
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Copiar archivos
COPY . /var/www/html/

# Configurar permisos y crear carpeta de uploads
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html/uploads \
    && chmod -R 777 /var/www/html

# Ejecutar instalador de base de datos
RUN php /var/www/html/semilla.php

EXPOSE 80