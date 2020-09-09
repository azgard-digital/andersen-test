#!/usr/bin/env bash

chmod -R 777 ./bootstrap/cache
chmod -R 777 ./storage

composer update --no-interaction &&
php artisan migrate --force
php-fpm
