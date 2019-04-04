FROM php:7.3

RUN apt-get update -qq  \
    && apt-get install -y -qq \
        libzip-dev \
        libicu-dev

RUN docker-php-ext-install \
       intl \
       zip

RUN pecl install \
        xdebug \
    && docker-php-ext-enable xdebug

ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
