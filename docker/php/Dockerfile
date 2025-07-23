FROM php:8.4-fpm

RUN apt-get update && apt-get install -y     git     unzip     zip     curl     libicu-dev     libzip-dev     libonig-dev     libxml2-dev     && docker-php-ext-install intl pdo pdo_mysql zip opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
