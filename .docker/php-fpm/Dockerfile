FROM php:fpm-alpine

WORKDIR /opt

RUN apk add --no-cache \
		$PHPIZE_DEPS \
		openssl-dev

RUN apk add mysql-client libmcrypt-dev \
    && pecl install xdebug mcrypt-1.0.3 \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable mysqli \
    && docker-php-ext-configure mysqli --with-mysql-sock=/var/run/mysqld/mysqld.sock \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-enable mcrypt

WORKDIR /opt

CMD php-fpm
EXPOSE 900
