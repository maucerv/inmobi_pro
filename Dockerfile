FROM php:8.2-apache

# 1. Instalar dependencias y drivers de SQLite
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# 2. Habilitar mod_rewrite para URLs amigables
RUN a2enmod rewrite

# 3. Configurar el DocumentRoot de Apache
# Render espera que tu app esté en /var/www/html por defecto
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Copiar los archivos del proyecto
COPY . /var/www/html/

# 5. Permisos de escritura específicos para la base de datos SQLite
# En lugar de 777 a todo, damos permisos al usuario de apache solo donde es necesario
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/includes

# El puerto 80 es el estándar que Render busca
EXPOSE 80

CMD ["apache2-foreground"]