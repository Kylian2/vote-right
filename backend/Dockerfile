# Utiliser une image PHP officielle avec PHP-FPM
FROM php:8.2-fpm-alpine

# Installer les outils nécessaires (git, unzip, etc.)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Installer Composer (gestionnaire de dépendances PHP)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail dans le conteneur
WORKDIR /backend

# Copier les fichiers nécessaires pour l'installation des dépendances
COPY ./backend/composer.json ./backend/composer.lock /backend/

# Installer les dépendances PHP via Composer
RUN composer install --no-dev --optimize-autoloader

# Copier le reste du projet dans le conteneur
COPY ./backend /backend

# Exposer le port par défaut de PHP-FPM
EXPOSE 9000
