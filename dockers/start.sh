#!/usr/bin/env bash

chmod -R 777 ./bootstrap/cache
chmod -R 777 ./storage

composer update --no-interaction --optimize-autoloader --no-dev &&
php artisan migrate --force &&
php artisan key:generate &&
php artisan passport:install
php artisan config:clear
php artisan config:cache
php artisan route:cache

/usr/bin/supervisord -c /etc/supervisor/supervisord.conf
