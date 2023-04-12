FROM php:8.2-fpm-alpine

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


ENV PHP_UPLOAD_MAX_FILESIZE=20M
ENV PHP_POST_MAX_SIZE=24M
ENV PHP_MEMORY_LIMIT=512M
ENV PHP_MAX_EXECUTION_TIME=300

RUN apk add --no-cache \
    $PHPIZE_DEPS \
    freetype-dev \
    libzip-dev \
    openssl-dev \
    postgresql-dev

RUN pecl install \
    redis

RUN docker-php-ext-configure zip

RUN docker-php-ext-install \
    pdo \
    zip \
    pcntl \
    bcmath \
    pdo_pgsql

RUN docker-php-ext-enable \
    redis

COPY ./build/php/memory_expand.ini $PHP_INI_DIR/conf.d

WORKDIR /var/www
