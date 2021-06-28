# Opinionated ReactPHP optimised PHP Docker images

[![Github Actions](https://github.com/WyriHaximusNet/docker-php/workflows/Continuous%20Integration/badge.svg?event=push)](https://github.com/wyrihaximusnet/docker-php/actions)
[![Docker hub](https://img.shields.io/badge/Docker%20Hub-00a5c9.svg?logo=docker&style=flat&color=00a5c9&labelColor=00a5c9&logoColor=white)](https://hub.docker.com/r/wyrihaximusnet/php/)
[![Docker hub](https://img.shields.io/docker/pulls/wyrihaximusnet/php.svg?color=00a5c9&labelColor=03566a)](https://hub.docker.com/r/wyrihaximusnet/php/)
[![Docker hub](https://img.shields.io/microbadger/image-size/wyrihaximusnet/php/7.4-zts-alpine3.12.svg?color=00a5c9&labelColor=03566a)](https://hub.docker.com/r/wyrihaximusnet/php/)

# Images

This repo builds two different images, plus a `-dev` image for each containing [`composer`](https://getcomposer.org/), 
bash, git, ssh, strace, gdb, and make, and a `-root` image for all `*(-dev)` images where the default user is root. All the images 
are based on [`Alpine Linux`](https://alpinelinux.org/) and [`Debian Linux`](https://www.debian.org/). All images come with 
extensions used to increase the performance of [`ReactPHP`](https://reactphp.org/) (such as event loop extensions). Such extensions 
are highlighted **build** in the extensions list below. All the `Alpine Linux` images are scanned for vulnerabilities, and not pushed 
if any are found. The `Debian Linux` containers easily a few hundred so those aren't scanned. (There is no use in doing so.)

## Images News

Sometimes big changes happen to images, to stay informed please subscribe to this thread: https://github.com/WyriHaximusNet/docker-php/issues/46

### CVE Matrix

Currently Alpine and Debian images are treated differently, this matrix defines the differences between Alpine and Debian images:

| Base Image | Description                                                      |
|------------|------------------------------------------------------------------|
| Alpine     | Don't push when CVE's are found when building                    |
| Debian     | Not scanned for CVE's due to the ton of low CVE's found in there |

## The available tags

The docker registry prefix is `wyrihaximusnet/php`, thus `wyrihaximusnet/php:OUR-TAGS`

In order to provide upgrade path we intend to keep one or more versions of PHP.

[Currently Available tags on Docker hub](https://hub.docker.com/r/wyrihaximusnet/php/tags/)

The tag naming strategy consists of (Read as a regex):

- PHP: `(phpMajor).(phpMinor)-(nts|zts)-(alpine(alpineMajor).(alpineMinor)|buster|strech)(-slim)(-dev)(-root)?`
  - Example: `7.3-fpm-alpine3.12`, `7.4-fpm-alpine3.13-dev`, `8.0-zts-buster-slim`

## Example usage

The following example has two build staging, the first for leading in any required dependencies, and the second the 
actual image we'd want to use. In the second stage we copy the dependencies in without needing composer in the 
production image. We create the image with the following command:

```bash
docker build . -t IMAGE_NAME:TAG --target=runtime
```

```dockerfile
FROM wyrihaximusnet/php:7.4-zts-alpine3.13-slim-dev AS install-dependencies

WORKDIR /opt/app

COPY ./composer.lock /opt/app/composer.lock
COPY ./composer.json /opt/app/composer.json
COPY ./src/ /opt/app/src/
RUN composer install --ansi --no-interaction --prefer-dist --no-dev -o

FROM wyrihaximusnet/php:7.4-zts-alpine3.13-slim AS runtime

WORKDIR /opt/app

COPY ./composer.lock /opt/app/composer.lock
COPY ./composer.json /opt/app/composer.json
COPY --from=install-dependencies /opt/app/vendor/ /opt/app/vendor/
COPY ./src/ /opt/app/src/
COPY ./app.php /opt/app/app.php

ENTRYPOINT ["php", "/opt/app/app.php"]
```

### NTS

NTS, or non-thread safe is the PHP version most people use. This image comes with the following extensions:

| Extension    | Description                              |
|--------------|------------------------------------------|
| ext-eio      | Provides interface to the libeio library |
| ext-pcntl    | PCNTL OS signals                         |
| ext-uv       | LibUV event loop                         |

### ZTS

ZTS, or zend thread safe is the PHP version that is safe to be used and required my threading extensions such as 
pthreads or parallel. This image comes with the following extensions:

| Extension    | Description                                                |
|--------------|------------------------------------------------------------|
| ext-eio      | Provides interface to the libeio library                   |
| ext-parallel | A succinct parallel concurrency API for PHP7 using threads |
| ext-pcntl    | PCNTL OS signals                                           |
| ext-uv       | LibUV event loop                                           |

Both versions come with the following list of non-non-blocking related (core-) extensions:

* bcmath
* Core
* ctype
* curl
* date
* dom
* fileinfo
* filter
* ftp
* gd
* gmp
* hash
* iconv
* json
* libxml
* mbstring
* mysqlnd
* openssl
* pcre
* PDO
* pdo_pgsql
* pdo_sqlite
* pgsql
* Phar
* posix
* readline
* Reflection
* session
* SimpleXML
* sodium
* SPL
* sqlite3
* standard
* tokenizer
* vips
* xml
* xmlreader
* xmlwriter
* zip
* zlib

# Slim images

Slim images include all the above extensions except the following, as those notoriously require heavy dependencies:

* gd
* vips

# Credits

This project is based on [Usabilla](https://usabilla.com/)'s [PHP Docker Template](https://github.com/usabilla/php-docker-template).
Lots of the documentation on that repository also applies here, with the big difference that this project only 
supplies CLI images.
