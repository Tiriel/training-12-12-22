#!/bin/sh

set -ex

apk add --no-cache --update \
    git \
    unzip \
    libzip-dev \
    zlib-dev \
    ca-certificates \
    openssh-client

docker-php-ext-install zip

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer --quiet
rm composer-setup.php
