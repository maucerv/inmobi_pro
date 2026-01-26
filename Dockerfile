# Usamos una versión oficial de PHP con Apache
FROM php:8.2-apache

# 1. INSTALACIÓN DE DRIVERS
# Instalamos las librerías de SQLite y el driver de PHP necesario
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    unzip \
    && docker-php-ext-install pdo pdo_sqlite

# 2. CONFIGURACIÓN DE APACHE
# Activamos mod_rewrite por si usas URLs amigables
RUN a2enmod rewrite

# 3. COPIAR ARCHIVOS
# Copiamos todo tu código al contenedor
COPY . /var/www/html/

# 4. PERMISOS (CRÍTICO PARA RENDER)
# Damos permisos totales a la carpeta HTML para que PHP pueda
# crear el archivo 'inmobiliaria_lite.db' sin errores de permisos.
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html

# 5. PUERTO
EXPOSE 80

# 6. ARRANQUE
CMD ["apache2-foreground"]