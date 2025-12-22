FROM php:8.2-apache

# Instalamos extensiones
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite

# ESTA ES LA CLAVE:
# Copiamos el CONTENIDO de src (no la carpeta src) a la raíz pública
COPY src/ /var/www/html/

# Ajustamos permisos
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 755 /var/www/html/

# Le decimos a Apache que escuche en el puerto correcto
ENV PORT=80
EXPOSE 80
