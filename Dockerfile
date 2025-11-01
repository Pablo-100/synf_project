# Utiliser une image PHP avec Apache
FROM php:8.2-apache

# Définir les variables d'environnement
ENV APP_ENV=prod \
    APP_DEBUG=0 \
    COMPOSER_ALLOW_SUPERUSER=1

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

# Créer un fichier .env par défaut s'il n'existe pas
RUN if [ ! -f .env ]; then \
        echo "APP_ENV=prod" > .env && \
        echo "APP_DEBUG=0" >> .env && \
        echo "APP_SECRET=CHANGE_ME_IN_PRODUCTION" >> .env && \
        echo "DATABASE_URL=mysql://root:root@localhost:3306/synf_project?serverVersion=8.0&charset=utf8mb4" >> .env; \
    fi

# Créer les dossiers nécessaires avec permissions
RUN mkdir -p public/uploads var/cache var/log \
    && chmod -R 777 public/uploads var/cache var/log

# Configurer Apache pour pointer vers public/
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Configurer AllowOverride pour .htaccess
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Définir les permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html/var \
    && chmod -R 755 /var/www/html

# Configurer PHP pour afficher les erreurs (temporairement pour debug)
RUN echo "display_errors = On" >> /usr/local/etc/php/php.ini-production \
    && echo "display_startup_errors = On" >> /usr/local/etc/php/php.ini-production \
    && echo "error_reporting = E_ALL" >> /usr/local/etc/php/php.ini-production \
    && cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# Warmup du cache Symfony
RUN php bin/console cache:warmup --env=prod --no-debug || true \
    && chown -R www-data:www-data var/cache var/log

# Copier le script d'entrée
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Exposer le port 80
EXPOSE 80

# Utiliser le script d'entrée
ENTRYPOINT ["docker-entrypoint.sh"]
