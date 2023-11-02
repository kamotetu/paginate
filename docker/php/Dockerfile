FROM php:7.2-apache

RUN apt-get update && \
    apt-get install -y libzip-dev zlib1g-dev && \
    docker-php-ext-install mysqli pdo_mysql zip && \
    a2enmod rewrite

RUN curl -sS https://getcomposer.org/installer \
    |  php -- \
        --filename=composer \
        --install-dir=/usr/local/bin \
    && chown www-data /var/www \
    && chmod g+s /var/www/html
