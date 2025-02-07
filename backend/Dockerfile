FROM php:8.2-fpm-alpine

# Installation des dépendances
RUN apk add --no-cache \
    nginx \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Installation de Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configuration de Nginx -- à peut etre retirer à terme pour en monter une spécifique
COPY ./config/nginx.conf /etc/nginx/nginx.conf

WORKDIR /var/www/html

COPY ./composer.json ./composer.lock ./
RUN composer install --no-dev --optimize-autoloader --prefer-dist

COPY . .

EXPOSE 80

# Lancer Nginx et PHP-FPM ensemble
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]