FROM php:8.2-apache

# Instalar extensiones para PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Copiar SOLO el contenido de /api al document root
COPY api/ /var/www/html/

# Permitir .htaccess
RUN echo "<Directory /var/www/html/> \
    AllowOverride All \
</Directory>" >> /etc/apache2/apache2.conf

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Permisos
RUN chown -R www-data:www-data /var/www/html

# Puerto
EXPOSE 80

CMD ["apache2-foreground"]
