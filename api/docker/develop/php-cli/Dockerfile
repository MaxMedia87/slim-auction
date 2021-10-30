FROM php:7.4-cli-alpine

RUN apk update && apk add autoconf g++ make \
    && pecl install xdebug-2.9.0 \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable xdebug

RUN apk add unzip

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./conf.d /usr/local/etc/php/conf.d

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet

WORKDIR /app
