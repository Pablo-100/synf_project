# Utiliser une image PHP avec Apache
FROM php:8.2-apache

# Installer les extensions nécessaires à Symfony
RUN docker-php-ext-install pdo pdo_mysql

# Installer les extensions supplémentaires
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install intl zip \
    && docker-php-ext-enable intl

# Activer mod_rewrite pour Apache (requis pour Symfony)
RUN a2enmod rewrite

# Copier le projet dans le conteneur
COPY . /var/www/html/

# Définir le répertoire de travail
WORKDIR /var/www/html/

# Installer Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Créer le dossier uploads avec permissions
RUN mkdir -p public/uploads && chmod -R 775 public/uploads

# Configurer Apache pour pointer vers public/
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Définir les permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Vider le cache Symfony
RUN php bin/console cache:clear --env=prod --no-debug || true

# Exposer le port 80
EXPOSE 80

# Lancer Apache
CMD ["apache2-foreground"]
