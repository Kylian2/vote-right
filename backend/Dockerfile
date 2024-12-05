# Étape 1 : Construction des dépendances avec Composer
FROM composer:2 as builder

WORKDIR /app

COPY ./composer.json ./composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Étape 2 : Construction de l'image PHP
FROM php:8.1-fpm

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Copier Composer de l'image précédente
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier les dépendances installées de l'image builder
COPY --from=builder /app /app

# Définir le répertoire de travail
WORKDIR /app

# Exposer le port
EXPOSE 9000

# Commande pour démarrer PHP-FPM
CMD ["php-fpm"]
