FROM php:8.2-apache

# 1. Instalar dependencias del sistema y SQLite
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    unzip \
    && docker-php-ext-install pdo pdo_sqlite \
    && docker-php-ext-enable pdo_sqlite

# 2. Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# 3. Copiar los archivos de la aplicación
COPY . /var/www/html/

# 4. Crear carpeta uploads y dar permisos (CRÍTICO para SQLite)
# SQLite necesita escribir en la CARPETA que contiene el archivo, no solo en el archivo.
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html

# 5. IMPORTANTE: No ejecutamos semilla.php aquí para evitar el error de build.
#    Lo haremos en el comando de inicio.

EXPOSE 80

# 6. Comando de inicio: Configura la BD y arranca Apache
CMD php /var/www/html/semilla.php && apache2-foreground