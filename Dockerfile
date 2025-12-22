# Usamos la imagen oficial de PHP con Apache
FROM php:8.2-apache

# 1. Instalar extensiones necesarias para MySQL y otras utilidades
RUN docker-php-ext-install pdo pdo_mysql mysqli

# 2. Habilitar mod_rewrite de Apache (para URLs amigables si las usas)
RUN a2enmod rewrite

# 3. Configurar Apache para que escuche en el puerto 80 (Estándar de Render)
# Render asigna un puerto dinámico, pero mapea internamente al 80 del contenedor por defecto.
ENV PORT=80

# 4. Copiar el código fuente de tu carpeta 'src' a la carpeta pública de Apache
COPY src/ /var/www/html/

# 5. Ajustar permisos para que Apache pueda leer los archivos
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 755 /var/www/html/

# 6. (Opcional) Configuración recomendada de PHP para producción
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# El contenedor se ejecuta automáticamente con el comando default de Apache