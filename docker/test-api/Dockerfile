FROM php:7.2-fpm

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG USERID=1000
RUN usermod -u $USERID www-data

RUN mkdir /var/www/.composer && chown -R www-data /var/www/.composer
ENV COMPOSER_HOME /var/www/.composer

RUN apt-get update && apt-get install -y git zlib1g-dev \
    && docker-php-ext-install zip

RUN docker-php-ext-install pcntl

RUN docker-php-ext-install pdo_mysql

USER www-data

WORKDIR /var/www/violations
