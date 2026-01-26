FROM php:8.2-apache

# 1. Instalar SQLite y Drivers necesarios
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    unzip \
    && docker-php-ext-install pdo pdo_sqlite

# 2. Configurar Apache (URLs amigables)
RUN a2enmod rewrite

# 3. Copiar TODO al contenedor
COPY . /var/www/html/

# 4. Asignar Permisos Totales (Para evitar errores de escritura en DB)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html

EXPOSE 80

# 5. SCRIPT DE AUTO-LOCALIZACIÓN (LA SOLUCIÓN)
# Busca el archivo 'index.php' en las carpetas y le dice a Apache que esa es la raíz.
CMD ["/bin/bash", "-c", "\
    echo '🔍 Buscando dónde está el archivo index.php...'; \
    TARGET_DIR=$(dirname $(find /var/www/html -maxdepth 3 -name index.php | head -n 1)); \
    if [ -z \"$TARGET_DIR\" ]; then \
        echo '⚠️ No se encontró index.php, usando raíz por defecto.'; \
        TARGET_DIR='/var/www/html'; \
    fi; \
    echo \"✅ Sitio encontrado en: $TARGET_DIR\"; \
    echo \"🔧 Configurando Apache para usar esa carpeta...\"; \
    sed -i \"s|/var/www/html|$TARGET_DIR|g\" /etc/apache2/sites-available/000-default.conf; \
    echo '🚀 Iniciando Servidor...'; \
    apache2-foreground"]