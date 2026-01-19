FROM php:8.2-apache

# 1. Instalar dependencias y activar SQLite explícitamente
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    unzip \
    && docker-php-ext-install pdo pdo_sqlite \
    && docker-php-ext-enable pdo_sqlite

# 2. Habilitar URLs limpias en Apache
RUN a2enmod rewrite

# 3. Copiar los archivos de tu proyecto
COPY . /var/www/html/

# 4. Configurar permisos iniciales de carpetas
# Damos permiso total a la carpeta raíz para que SQLite pueda crear archivos temporales
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html

# 5. Exponer el puerto
EXPOSE 80

# 6. COMANDO DE INICIO (AQUÍ ESTÁ LA CORRECCIÓN)
# Ejecutamos semilla, Y LUEGO cambiamos el dueño del archivo .sqlite a www-data
CMD php /var/www/html/semilla.php && \
    chown www-data:www-data /var/www/html/inmobiliaria.sqlite && \
    chmod 666 /var/www/html/inmobiliaria.sqlite && \
    apache2-foreground