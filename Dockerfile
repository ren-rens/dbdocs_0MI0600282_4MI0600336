FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    default-jdk \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . /var/www/html/

RUN mkdir -p /var/www/html/storage && chmod -R 777 /var/www/html/storage
