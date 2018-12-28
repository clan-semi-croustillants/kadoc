FROM php:7.2-fpm-alpine

# Xdebug
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV PHP_XDEBUG_DEFAULT_ENABLE ${PHP_XDEBUG_DEFAULT_ENABLE:-1}
ENV PHP_XDEBUG_REMOTE_ENABLE ${PHP_XDEBUG_REMOTE_ENABLE:-1}
ENV PHP_XDEBUG_REMOTE_HOST ${PHP_XDEBUG_REMOTE_HOST:-"127.0.0.1"}
ENV PHP_XDEBUG_REMOTE_PORT ${PHP_XDEBUG_REMOTE_PORT:-9000}
ENV PHP_XDEBUG_REMOTE_AUTO_START ${PHP_XDEBUG_REMOTE_AUTO_START:-0}
ENV PHP_XDEBUG_REMOTE_CONNECT_BACK ${PHP_XDEBUG_REMOTE_CONNECT_BACK:-0}
ENV PHP_XDEBUG_IDEKEY ${PHP_XDEBUG_IDEKEY:-"docker"}
ENV PHP_XDEBUG_PROFILER_ENABLE ${PHP_XDEBUG_PROFILER_ENABLE:-0}
ENV PHP_XDEBUG_PROFILER_OUTPUT_DIR ${PHP_XDEBUG_PROFILER_OUTPUT_DIR:-"/tmp"}

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive
ARG AMQP_TAG=v2.7.2

# Install selected extensions and other stuff
RUN set -ex \
    && apk update \
    && apk upgrade \
    && apk add git \
    && apk --no-cache add postgresql-dev $PHPIZE_DEPS autoconf zlib-dev icu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_pgsql bcmath intl \
    && apk del postgresql-dev \
    && apk add --no-cache --upgrade --update-cache postgresql git \
    && docker-php-source extract \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk add --no-cache --no-progress rabbitmq-c-dev \
    && pecl install amqp \
    && docker-php-ext-enable amqp

# Fix file owner
ARG unix_ID=1000
RUN echo http://dl-2.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories
RUN apk --no-cache add shadow && usermod -u $unix_ID www-data

USER www-data

COPY --from=composer:1.7 /usr/bin/composer /usr/bin/composer
RUN composer global require "pyrech/composer-changelogs"

COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug-dev.ini
COPY php-ini-overrides.ini /etc/php/7.2/fpm/conf.d/99-overrides.ini
