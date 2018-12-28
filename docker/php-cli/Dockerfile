FROM php:7.2-cli-alpine

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN set -ex \
    && apk update \
    && apk upgrade \
    && apk add git \
    && apk --no-cache add postgresql-dev $PHPIZE_DEPS zlib-dev \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && apk del postgresql-dev \
    && apk --no-cache add --upgrade --update-cache postgresql \
    && docker-php-source extract

# Fix file owner
ARG unix_ID=1000
RUN echo http://dl-2.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories
RUN apk --no-cache add shadow && usermod -u $unix_ID www-data

USER www-data

COPY --from=composer:1.7 /usr/bin/composer /usr/bin/composer

COPY php-ini-overrides.ini /etc/php/7.2/fpm/conf.d/99-overrides.ini
