FROM php:7.4-fpm-alpine

COPY php.ini /usr/local/etc/php/php.ini

ENV PHPREDIS_VERSION 3.0.0

#libs and php-ext
RUN apk add --no-cache icu \
                       gettext \
                       mysql-client \
                       imagemagick \
                       imagemagick-dev &&\
    apk add --no-cache --virtual build-deps \
                                 autoconf \
                                 gcc \
                                 g++ \
                                 make \
                                 pkgconfig \
                                 icu-dev \
                                 oniguruma-dev \
                                 gettext-dev &&\
    docker-php-ext-install intl \
                           opcache \
                           calendar \
                           exif \
                           pdo \
                           pdo_mysql \
                           mysqli \
                           gettext \
                           sockets &&\
    pecl install imagick &&\
    docker-php-ext-enable imagick &&\
    docker-php-ext-enable mysqli &&\
    apk del build-deps &&\
    rm -rf /tmp/* /usr/local/lib/php/doc/* /var/cache/apk/*
 # Install Memcached
ENV MEMCACHED_DEPS zlib-dev libmemcached-dev cyrus-sasl-dev
RUN apk add --no-cache --update libmemcached-libs zlib
RUN set -xe \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && apk add --no-cache --update --virtual .memcached-deps $MEMCACHED_DEPS \
    && pecl install memcached \
    && echo "extension=memcached.so" > /usr/local/etc/php/conf.d/20_memcached.ini \
    && rm -rf /usr/share/php7 \
    && rm -rf /tmp/* \
    && apk del .memcached-deps .phpize-deps

WORKDIR /data
