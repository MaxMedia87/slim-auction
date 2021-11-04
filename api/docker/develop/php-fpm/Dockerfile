FROM php:7.4-fpm-alpine

RUN apk update && apk add autoconf g++ make \
    && pecl install xdebug-2.9.0 \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable xdebug

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./conf.d /usr/local/etc/php/conf.d

WORKDIR /app

#Здесь прописываем какой скрипт должен копироваться и запускатьcя при запуске данного образа
COPY ./entrypoint.sh /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint
