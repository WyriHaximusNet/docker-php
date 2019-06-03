FROM php:7.3-cli-alpine3.9 AS build-uv
RUN apk update && \
    apk add --no-cache $PHPIZE_DEPS git libuv-dev && \
    git clone https://github.com/bwoebi/php-uv uv
WORKDIR /uv
RUN git fetch \
    && git pull \
    && phpize \
    && ./configure \
    && make install \
    && EXTENSION_DIR=`php-config --extension-dir 2>/dev/null` && \
    cp "$EXTENSION_DIR/uv.so" /uv.so
RUN sha256sum /uv.so

FROM php:7.3-cli-alpine3.9 AS nts
RUN set -x \
    && addgroup -g 1000 app \
    && adduser -u 1000 -D -G app app
COPY --from=build-uv /uv.so /uv.so

# Patch CVE-2018-14618 (curl), CVE-2018-16842 (libxml2), CVE-2019-1543 (openssl)
RUN apk upgrade --no-cache curl libxml2 openssl

# Install docker help scripts
COPY src/php/utils/docker/ /usr/local/bin/

COPY src/php/conf/ /usr/local/etc/php/conf.d/
COPY src/php/cli/conf/*.ini /usr/local/etc/php/conf.d/

RUN EXTENSION_DIR=`php-config --extension-dir 2>/dev/null` && \
	mv /*.so "$EXTENSION_DIR/" && \
	apk add --no-cache \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        gmp-dev \
        zlib-dev \
        icu-dev \
        postgresql-dev \
        libzip-dev \
        libuv-dev \
        coreutils \
        procps \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd pcntl pgsql pdo intl pdo_pgsql bcmath zip gmp iconv \
    && docker-php-ext-enable uv \
    && rm -rf /var/cache/apk/*

# Install shush
COPY src/php/utils/install-shush /usr/local/bin/
RUN install-shush && rm -rf /usr/local/bin/install-shush

STOPSIGNAL SIGTERM

USER app

ENTRYPOINT ["/usr/local/bin/shush", "exec", "docker-php-entrypoint"]

## NTS-DEV STAGE ##
FROM nts AS nts-dev

USER root

RUN apk add \
        make \
        git \
        openssh-client \
        bash

# Install Xdebug and development specific configuration
RUN docker-php-dev-mode xdebug \
    && docker-php-dev-mode config

# Install Docker and Docker Compose
RUN apk add --no-cache docker py-pip python-dev libffi-dev openssl-dev gcc libc-dev make \
    && pip install docker-compose

# Install composer
COPY src/php/utils/install-composer /usr/local/bin/
RUN install-composer && rm -rf /usr/local/bin/install-composer

USER app

RUN composer global require hirak/prestissimo --ansi --no-progress

# Change entrypoint back to the default because we don't need shush in development
ENTRYPOINT ["docker-php-entrypoint"]