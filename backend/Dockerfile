# Utiliser une image PHP officielle avec PHP-FPM
FROM php:8.1-fpm

# Installer les outils nécessaires (git, unzip, etc.)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Installer Composer (gestionnaire de dépendances PHP)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail où les fichiers de l'application PHP seront
WORKDIR /backend  # Utiliser /backend comme répertoire de travail

# Copier uniquement les fichiers composer.json et composer.lock pour installer les dépendances
COPY ./backend/composer.json ./backend/composer.lock ./backend/

# Installer les dépendances PHP via Composer
RUN composer install --no-dev --optimize-autoloader

# Copier tout le projet dans le conteneur
COPY ./backend ./backend

# Exposer le port par défaut de PHP-FPM
EXPOSE 9000

# Commande par défaut pour démarrer PHP-FPM
CMD ["php-fpm"]
