FROM php:8.2-apache

RUN echo "deb http://archive.debian.org/debian/ stretch main" > /etc/apt/sources.list && \
    echo "deb http://archive.debian.org/debian-security stretch/updates main" >> /etc/apt/sources.list && \
    apt-get update && \
    apt-get install -y libzip-dev zlib1g-dev && \
    docker-php-ext-install mysqli pdo_mysql zip && \
    a2enmod rewrite