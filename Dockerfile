FROM php:8.3.1-apache
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ARG DEBIAN_FRONTEND=noninteractive

RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql

RUN apt-get update \
    && apt-get install -y sendmail libpng-dev \
    && apt-get install -y libzip-dev \
    && apt-get install -y zlib1g-dev \
    && apt-get install -y libonig-dev \
    && docker-php-ext-install zip \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install zip \
    && docker-php-ext-install gd \
    && rm -rf /var/lib/apt/lists/*

RUN touch /tmp/xdebug.log

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN a2enmod rewrite
