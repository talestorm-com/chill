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
RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis

WORKDIR /data
