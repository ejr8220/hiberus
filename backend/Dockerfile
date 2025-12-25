FROM php:8.2-apache

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev \
 && docker-php-ext-install pdo pdo_pgsql \
 && a2enmod rewrite headers

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar proyecto con permisos correctos
COPY --chown=www-data:www-data . /var/www/html

# Instalar dependencias de Symfony
RUN composer install --no-interaction --no-progress --prefer-dist

# Crear carpeta var y ajustar permisos
RUN mkdir -p var \
 && chown -R www-data:www-data var

# Ajustar DocumentRoot a public/
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Exponer puerto Apache
EXPOSE 80

# Comando de arranque
CMD ["apache2-foreground"]