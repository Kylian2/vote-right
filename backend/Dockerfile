FROM php:8.2-fpm-alpine

# Installer les dépendances nécessaires
RUN apk update && apk add --no-cache \
    libzip-dev \
    libpng-dev \
    git \
    unzip \
    && docker-php-ext-install zip pdo pdo_mysql gd

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /backend

# Copier les fichiers nécessaires pour Composer
COPY ./backend/composer.json ./backend/composer.lock /backend/

# Installer les dépendances PHP via Composer
RUN composer install --no-dev --optimize-autoloader

# Copier tout le projet dans le conteneur
COPY ./backend /backend

# Exposer le port de PHP-FPM
EXPOSE 9000
