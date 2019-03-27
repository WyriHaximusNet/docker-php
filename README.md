# Opinionated ReactPHP optimised PHP Docker images

[![CircleCI](https://circleci.com/gh/WyriHaximusNet/docker-php.svg?style=svg)](https://circleci.com/gh/WyriHaximusNet/docker-php)
[![Docker hub](https://img.shields.io/badge/Docker%20Hub-00a5c9.svg?logo=docker&style=flat&color=00a5c9&labelColor=00a5c9&logoColor=white)](https://hub.docker.com/r/wyrihaximusnet/php/)
[![Docker hub](https://img.shields.io/docker/pulls/wyrihaximusnet/php.svg?color=00a5c9&labelColor=03566a)](https://hub.docker.com/r/wyrihaximusnet/php/)
[![Docker hub](https://img.shields.io/microbadger/image-size/wyrihaximusnet/php/7.3-zts-alpine3.9.svg?color=00a5c9&labelColor=03566a)](https://hub.docker.com/r/wyrihaximusnet/php/)

# Images

This repo builds two different images, plus a `-dev` image for each containing [`composer`](https://getcomposer.org/), bash, git, ssh, and make. All 
the images are based on [`Alpine Linux`](https://alpinelinux.org/). All images come with extensions used to increase 
the performance of [`ReactPHP`](https://reactphp.org/) (such as event loop extensions). Such extensions are highlighted 
**build** in the extensions list below.


## The available tags

The docker registry prefix is `wyrihaximusnet/php`, thus `wyrihaximusnet/php:OUR-TAGS`

In order to provide upgrade path we intend to keep one or more versions of PHP.

[Currently Available tags on Docker hub](https://hub.docker.com/r/wyrihaximusnet/php/tags/)

The tag naming strategy consists of (Read as a regex):

- PHP: `(phpMajor).(phpMinor)-(nts|zts)-(alpine|future supported OSes)(alpineMajor).(alpineMinor)(-dev)?`
  - Example: `7.2-fpm-alpine3.8`, `7.2-fpm-alpine3.8-dev`


### NTS

NTS, or non-thread safe is the PHP version most people use. This image comes with the following extensions:

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
* pcntl
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
* **uv**
* xml
* xmlreader
* xmlwriter
* zip
* zlib

### ZTS

NTS, or zend thread safe is the PHP version that is safe to be used and required my threading extensions such as 
pthreads or parallel. This image comes with the following extensions:

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
* **parallel**
* pcntl
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
* **uv**
* xml
* xmlreader
* xmlwriter
* zip
* zlib

# Credits

This project is based on [Usabilla](https://usabilla.com/)'s [PHP Docker Template](https://github.com/usabilla/php-docker-template).
Lots of the documentation on that repository also applies here, with the big difference that this project only 
supplies CLI images.
