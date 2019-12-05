# Opinionated ReactPHP optimised PHP Docker images

[![Github Actions](https://github.com/WyriHaximusNet/docker-php/workflows/Continuous%20Integration/badge.svg)](https://github.com/wyrihaximusnet/docker-php/actions)
[![Docker hub](https://img.shields.io/badge/Docker%20Hub-00a5c9.svg?logo=docker&style=flat&color=00a5c9&labelColor=00a5c9&logoColor=white)](https://hub.docker.com/r/wyrihaximusnet/php/)
[![Docker hub](https://img.shields.io/docker/pulls/wyrihaximusnet/php.svg?color=00a5c9&labelColor=03566a)](https://hub.docker.com/r/wyrihaximusnet/php/)
[![Docker hub](https://img.shields.io/microbadger/image-size/wyrihaximusnet/php/7.3-zts-alpine3.9.svg?color=00a5c9&labelColor=03566a)](https://hub.docker.com/r/wyrihaximusnet/php/)

# Images

This repo builds two different images, plus a `-dev` image for each containing [`composer`](https://getcomposer.org/), 
bash, git, ssh, and make, and a `-root` image for all `*(-dev)` images where the default user is root. All the images 
are based on [`Alpine Linux`](https://alpinelinux.org/). All images come with extensions used to increase the 
performance of [`ReactPHP`](https://reactphp.org/) (such as event loop extensions). Such extensions are highlighted 
**build** in the extensions list below.

## Images News

Sometimes big changes happen to images, to stay informed please subscribe to this thread: https://github.com/WyriHaximusNet/docker-php/issues/46

## The available tags

The docker registry prefix is `wyrihaximusnet/php`, thus `wyrihaximusnet/php:OUR-TAGS`

In order to provide upgrade path we intend to keep one or more versions of PHP.

[Currently Available tags on Docker hub](https://hub.docker.com/r/wyrihaximusnet/php/tags/)

The tag naming strategy consists of (Read as a regex):

- PHP: `(phpMajor).(phpMinor)-(nts|zts)-(alpine|future supported OSes)(alpineMajor).(alpineMinor)(-dev)(-root)?`
  - Example: `7.2-fpm-alpine3.8`, `7.2-fpm-alpine3.8-dev`


### NTS

NTS, or non-thread safe is the PHP version most people use. This image comes with the following extensions:

| Extension    | Description      |
|--------------|------------------|
| ext-pcntl    | PCNTL OS signals |
| ext-uv       | LibUV event loop |

### ZTS

ZTS, or zend thread safe is the PHP version that is safe to be used and required my threading extensions such as 
pthreads or parallel. This image comes with the following extensions:

| Extension    | Description                                                |
|--------------|------------------------------------------------------------|
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
* intl
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

# Credits

This project is based on [Usabilla](https://usabilla.com/)'s [PHP Docker Template](https://github.com/usabilla/php-docker-template).
Lots of the documentation on that repository also applies here, with the big difference that this project only 
supplies CLI images.
