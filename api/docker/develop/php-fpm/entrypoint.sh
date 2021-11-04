#!/bin/sh
set -e

#Определяем IP адрес контейнера для работы xdebug. Пример взять отсюда https://github.com/bufferings/docker-access-host
HOST_DOMAIN="host.docker.internal"
if ! ping -q -c1 $HOST_DOMAIN > /dev/null 2>&1
then
  HOST_IP=$(ip route | awk 'NR==1 {print $3}')
  echo -e "$HOST_IP\t$HOST_DOMAIN" >> /etc/hosts
fi

#Взято из дефолтного файла образа https://github.com/docker-library/php/blob/master/7.4/alpine3.14/fpm/docker-php-entrypoint
# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

exec "$@"