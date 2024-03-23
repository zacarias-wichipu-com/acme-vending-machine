FROM php:8.3-fpm-alpine

WORKDIR /app

RUN apk --update upgrade \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    linux-headers \
    && pecl install xdebug \
    && apk del -f .build-deps

RUN apk --update upgrade \
    && apk add --no-cache \
    bash

RUN curl -sS https://get.symfony.com/cli/installer | bash -s - --install-dir /usr/local/bin

COPY etc/infrastructure/php/ /usr/local/etc/php/
