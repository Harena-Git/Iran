FROM php:8.1-apache

# Activer mod_rewrite
RUN a2enmod rewrite

# Configurer Apache pour autoriser les .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Installer les extensions PHP pour PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Redémarrer Apache pour appliquer les changements
RUN service apache2 restart
