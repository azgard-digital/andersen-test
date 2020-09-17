FROM php:7.3-fpm

WORKDIR /var/www

RUN usermod -u 1000 www-data
RUN groupmod -g 1000 www-data

RUN apt-get update -y \
    && apt-get install -y nginx \
    && apt-get install -y supervisor

RUN  echo "127.0.0.1 nginx-stable" >> /etc/hosts

COPY --chown=www-data:www-data ./  /var/www
COPY ./dockers/start.sh  /var/www/

COPY ./dockers/config/nginx/conf.d/sites.conf /etc/nginx/conf.d/sites.conf
COPY ./dockers/config/nginx/conf.d/upstream_local.conf /etc/nginx/conf.d/upstream.conf
COPY ./dockers/config/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./dockers/config/nginx/conf.d/site_params /etc/nginx/conf.d/site_params

COPY ./dockers/config/php/www.conf /usr/local/etc/php-fpm.d/www.conf

COPY ./dockers/config/supervisord/supervisord.conf /etc/supervisor/supervisord.conf

RUN docker-php-ext-install pdo_mysql

RUN pecl install apcu

RUN apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    wget \
    zlib1g-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    libmcrypt-dev \
    nano

RUN docker-php-ext-install opcache \
pdo \
pgsql \
pdo_pgsql \
intl

RUN docker-php-ext-enable apcu opcache

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN curl -sS https://getcomposer.org/installer | \
php -- --install-dir=/usr/bin/ --filename=composer

RUN chmod +x /var/www/start.sh

EXPOSE 80

ENV DB_HOST=postgresql
ENV DB_PORT=5432
ENV DB_DATABASE=db_test
ENV DB_USERNAME=postgres
ENV DB_PASSWORD=postgres
ENV DB_CONNECTION=pgsql
ENV APP_URL=mytest.local
ENV APP_ENV=prod
ENV APP_DEBUG=false

CMD ["/bin/bash", "/var/www/start.sh"]
