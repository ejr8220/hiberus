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

# Configuración adicional en Apache para permitir OPTIONS y CORS
RUN echo '<Directory /var/www/html/public>
    AllowOverride All
    Require all granted
</Directory>

<IfModule mod_headers.c>
    Header always set Access-Control-Allow-Origin "http://localhost:3000"
    Header always set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS"
    Header always set Access-Control-Allow-Headers "Content-Type, Authorization, X-Role, X-Customer-Id, Accept"
</IfModule>

# Manejo de preflight OPTIONS
RewriteEngine On
RewriteCond %{REQUEST_METHOD} OPTIONS
RewriteRule ^(.*)$ $1 [R=200,L]
' >> /etc/apache2/apache2.conf

# Exponer puerto Apache
EXPOSE 80

# Comando de arranque
CMD ["apache2-foreground"]