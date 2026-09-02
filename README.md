# Opinionated ReactPHP optimised PHP Docker images

[![Alpine](https://github.com/WyriHaximusNet/docker-php/actions/workflows/alpine.yml/badge.svg)](https://github.com/WyriHaximusNet/docker-php/actions/workflows/alpine.yml)
[![Docker hub](https://img.shields.io/badge/Docker%20Hub-00a5c9.svg?logo=docker&style=flat&color=00a5c9&labelColor=00a5c9&logoColor=white)](https://hub.docker.com/r/wyrihaximusnet/php/)
[![Docker hub](https://img.shields.io/docker/pulls/wyrihaximusnet/php.svg?color=00a5c9&labelColor=03566a)](https://hub.docker.com/r/wyrihaximusnet/php/)
[![Docker hub](https://img.shields.io/docker/image-size/wyrihaximusnet/php/8.4-zts-alpine-slim)](https://hub.docker.com/r/wyrihaximusnet/php/)

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

These images are published to [Docker Hub](https://hub.docker.com/r/wyrihaximusnet/php/tags/) as `wyrihaximusnet/php:OUR-TAGS` and to the [GitHub Container Registry](https://github.com/WyriHaximusNet/docker-php/pkgs/container/php) as `ghcr.io/wyrihaximusnet/php:OUR-TAGS`.

In order to provide upgrade path we intend to keep one or more versions of PHP.

The tag naming strategy consists of (Read as a regex):

- PHP: `(phpMajor).(phpMinor)-(nts|zts)-(alpine((alpineMajor).(alpineMinor))|bullseye|buster|strech)(-slim)(-dev)(-root)?`
  - Example: `8.2-zts-alpine-slim`, `8.4-nts-alpine3.13-dev`, `8.1-zts-buster-slim`

## Example usage

The following example has two build staging, the first for leading in any required dependencies, and the second the 
actual image we'd want to use. In the second stage we copy the dependencies in without needing composer in the 
production image. We create the image with the following command:

```bash
docker build . -t IMAGE_NAME:TAG --target=runtime
```

```dockerfile
FROM ghcr.io/wyrihaximusnet/php:8.4-zts-alpine-slim-dev AS install-dependencies

WORKDIR /opt/app

COPY ./composer.lock /opt/app/composer.lock
COPY ./composer.json /opt/app/composer.json
COPY ./src/ /opt/app/src/
RUN composer install --ansi --no-interaction --prefer-dist --no-dev -o

FROM ghcr.io/wyrihaximusnet/php:8.4-zts-alpine-slim AS runtime

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

| Extension | Description                              |
|-----------|------------------------------------------|
| ext-eio   | Provides interface to the libeio library |
| ext-pcntl | PCNTL OS signals                         |
| ext-uv    | LibUV event loop                         |
| ext-event | Libevent event loop                      |

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
* ffi 
* fileinfo
* filter
* ftp
* gd
* gmp
* grpc
* hash
* iconv
* json
* libxml
* mbstring
* mysqlnd
* openssl
* opentelemetry
* pcre
* PDO
* pdo_pgsql
* pdo_sqlite
* pgsql
* Phar
* posix
* protobuf
* random
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

# Installing extensions

These images come with both [`PECL`](https://www.php.net/manual/en/install.pecl.php) and [`PIE`](https://github.com/php/pie/blob/main/docs/usage.md) for any additional extension needs.

### Tag last pushed

The following table lists when each base tag was last pushed to [Docker Hub](https://hub.docker.com/r/wyrihaximusnet/php/tags/) and the [GitHub Container Registry](https://github.com/WyriHaximusNet/docker-php/pkgs/container/php). Timestamps are sourced from Docker Hub; both registries are updated in the same CI push. Variant tags such as `-dev`, `-slim`, and `-root` are omitted as they are all built and pushed at the same moment.

<!-- tag-last-pushed-table-start -->

| Tag | Last pushed (UTC) |
|-----|-------------------|
| `8.5-nts-alpine` | 2026-09-01 09:55 UTC |
| `8.5-nts-alpine3.24` | 2026-09-01 09:56 UTC |
| `8.5-nts-alpine3.23` | 2026-08-27 15:59 UTC |
| `8.5-nts-alpine3.22` | 2026-08-27 15:59 UTC |
| `8.5-nts-alpine3.21` | 2026-02-28 06:30 UTC |
| `8.5-nts-debian` | 2026-08-19 10:05 UTC |
| `8.5-nts-trixie` | 2026-08-19 10:02 UTC |
| `8.5-zts-alpine` | 2026-09-01 09:56 UTC |
| `8.5-zts-alpine3.24` | 2026-09-01 09:56 UTC |
| `8.5-zts-alpine3.23` | 2026-09-01 09:55 UTC |
| `8.5-zts-alpine3.22` | 2026-08-27 15:59 UTC |
| `8.5-zts-alpine3.21` | 2026-03-19 06:45 UTC |
| `8.5-zts-debian` | 2026-09-02 09:35 UTC |
| `8.5-zts-trixie` | 2026-09-02 09:35 UTC |
| `8.4-nts-alpine` | 2026-09-01 09:56 UTC |
| `8.4-nts-alpine3.24` | 2026-09-01 09:55 UTC |
| `8.4-nts-alpine3.23` | 2026-08-27 16:06 UTC |
| `8.4-nts-alpine3.22` | 2026-08-27 16:08 UTC |
| `8.4-nts-alpine3.21` | 2026-03-19 06:52 UTC |
| `8.4-nts-alpine3.20` | 2025-09-13 03:31 UTC |
| `8.4-nts-alpine3.19` | 2025-02-22 05:57 UTC |
| `8.4-nts-debian` | 2026-08-19 09:51 UTC |
| `8.4-nts-trixie` | 2026-08-19 09:50 UTC |
| `8.4-zts-alpine` | 2026-09-01 09:56 UTC |
| `8.4-zts-alpine3.24` | 2026-09-01 09:56 UTC |
| `8.4-zts-alpine3.23` | 2026-08-29 10:53 UTC |
| `8.4-zts-alpine3.22` | 2026-08-27 15:59 UTC |
| `8.4-zts-alpine3.21` | 2026-03-19 06:46 UTC |
| `8.4-zts-alpine3.20` | 2025-09-13 03:30 UTC |
| `8.4-zts-alpine3.19` | 2025-02-22 06:00 UTC |
| `8.4-zts-debian` | 2026-08-29 11:22 UTC |
| `8.4-zts-trixie` | 2026-08-29 11:22 UTC |
| `8.3-nts-alpine` | 2026-09-01 09:56 UTC |
| `8.3-nts-alpine3.24` | 2026-09-01 09:55 UTC |
| `8.3-nts-alpine3.23` | 2026-08-27 16:07 UTC |
| `8.3-nts-alpine3.22` | 2026-07-25 21:20 UTC |
| `8.3-nts-alpine3.21` | 2026-03-19 06:45 UTC |
| `8.3-nts-alpine3.20` | 2025-09-13 03:23 UTC |
| `8.3-nts-alpine3.19` | 2025-02-22 05:57 UTC |
| `8.3-nts-alpine3.18` | 2024-09-22 09:38 UTC |
| `8.3-nts-alpine3.17` | 2024-04-11 05:46 UTC |
| `8.3-nts-bullseye` | 2024-01-23 01:17 UTC |
| `8.3-nts-debian` | 2026-08-19 09:58 UTC |
| `8.3-nts-trixie` | 2026-08-19 10:05 UTC |
| `8.3-zts-alpine` | 2026-09-01 09:55 UTC |
| `8.3-zts-alpine3.24` | 2026-09-01 09:55 UTC |
| `8.3-zts-alpine3.23` | 2026-08-27 16:07 UTC |
| `8.3-zts-alpine3.22` | 2026-07-25 21:13 UTC |
| `8.3-zts-alpine3.21` | 2026-03-19 06:45 UTC |
| `8.3-zts-alpine3.20` | 2025-09-13 03:20 UTC |
| `8.3-zts-alpine3.19` | 2025-02-22 06:00 UTC |
| `8.3-zts-alpine3.18` | 2024-09-22 09:37 UTC |
| `8.3-zts-alpine3.17` | 2024-04-11 05:50 UTC |
| `8.3-zts-bullseye` | 2024-02-02 06:19 UTC |
| `8.3-zts-debian` | 2026-08-27 15:51 UTC |
| `8.3-zts-trixie` | 2026-08-27 15:52 UTC |
| `8.2-nts-alpine` | 2026-09-01 09:57 UTC |
| `8.2-nts-alpine3.24` | 2026-09-01 09:55 UTC |
| `8.2-nts-alpine3.23` | 2026-08-27 16:07 UTC |
| `8.2-nts-alpine3.22` | 2026-08-27 16:06 UTC |
| `8.2-nts-alpine3.21` | 2025-12-31 22:47 UTC |
| `8.2-nts-alpine3.20` | 2025-07-27 12:36 UTC |
| `8.2-nts-alpine3.19` | 2025-02-22 05:58 UTC |
| `8.2-nts-alpine3.18` | 2024-09-22 09:34 UTC |
| `8.2-nts-alpine3.17` | 2024-04-11 05:46 UTC |
| `8.2-nts-alpine3.16` | 2023-08-05 09:04 UTC |
| `8.2-nts-alpine3.15` | 2022-12-13 10:37 UTC |
| `8.2-nts-bullseye` | 2024-01-23 01:15 UTC |
| `8.2-nts-buster` | 2023-09-02 08:31 UTC |
| `8.2-nts-debian` | 2026-08-19 09:57 UTC |
| `8.2-nts-trixie` | 2026-08-19 09:58 UTC |
| `8.2-zts-alpine` | 2026-09-01 09:55 UTC |
| `8.2-zts-alpine3.24` | 2026-09-01 09:55 UTC |
| `8.2-zts-alpine3.23` | 2026-08-27 15:59 UTC |
| `8.2-zts-alpine3.22` | 2026-08-27 15:59 UTC |
| `8.2-zts-alpine3.21` | 2025-12-31 22:45 UTC |
| `8.2-zts-alpine3.20` | 2025-07-27 12:36 UTC |
| `8.2-zts-alpine3.19` | 2025-02-22 05:59 UTC |
| `8.2-zts-alpine3.18` | 2024-09-22 09:33 UTC |
| `8.2-zts-alpine3.17` | 2024-04-11 05:47 UTC |
| `8.2-zts-alpine3.16` | 2023-08-05 09:06 UTC |
| `8.2-zts-bullseye` | 2024-02-02 06:17 UTC |
| `8.2-zts-buster` | 2023-09-02 08:33 UTC |
| `8.2-zts-debian` | 2026-08-27 15:51 UTC |
| `8.2-zts-trixie` | 2026-08-27 15:51 UTC |
| `8.1-nts-alpine` | 2025-08-21 05:02 UTC |
| `8.1-nts-alpine3.21` | 2025-08-21 05:02 UTC |
| `8.1-nts-alpine3.20` | 2025-07-27 12:37 UTC |
| `8.1-nts-alpine3.19` | 2025-02-22 05:59 UTC |
| `8.1-nts-alpine3.18` | 2024-08-28 05:13 UTC |
| `8.1-nts-alpine3.17` | 2024-04-11 05:45 UTC |
| `8.1-nts-alpine3.16` | 2024-03-31 21:30 UTC |
| `8.1-nts-alpine3.15` | 2023-05-26 10:07 UTC |
| `8.1-nts-alpine3.14` | 2021-11-24 09:39 UTC |
| `8.1-nts-alpine3.13` | 2021-11-24 09:34 UTC |
| `8.1-nts-bullseye` | 2023-09-02 08:32 UTC |
| `8.1-nts-buster` | 2023-09-02 08:32 UTC |
| `8.1-nts-debian` | 2023-09-02 08:32 UTC |
| `8.1-zts-alpine` | 2025-08-21 05:03 UTC |
| `8.1-zts-alpine3.21` | 2025-08-21 05:02 UTC |
| `8.1-zts-alpine3.20` | 2025-07-27 12:37 UTC |
| `8.1-zts-alpine3.19` | 2025-02-22 05:57 UTC |
| `8.1-zts-alpine3.18` | 2024-08-28 05:14 UTC |
| `8.1-zts-alpine3.17` | 2024-04-11 05:46 UTC |
| `8.1-zts-alpine3.16` | 2024-03-31 21:32 UTC |
| `8.1-zts-bullseye` | 2023-09-09 06:19 UTC |
| `8.1-zts-buster` | 2023-09-02 08:32 UTC |
| `8.1-zts-debian` | 2023-09-02 08:33 UTC |
| `8.0-nts-alpine` | 2022-11-07 16:09 UTC |
| `8.0-nts-alpine3.16` | 2023-11-21 05:42 UTC |
| `8.0-nts-alpine3.15` | 2023-05-26 10:08 UTC |
| `8.0-nts-alpine3.14` | 2022-05-23 09:46 UTC |
| `8.0-nts-alpine3.13` | 2021-11-24 09:33 UTC |
| `8.0-nts-alpine3.12` | 2021-09-28 09:57 UTC |
| `8.0-nts-bullseye` | 2023-11-01 13:12 UTC |
| `8.0-nts-buster` | 2023-11-01 13:12 UTC |
| `8.0-nts-debian` | 2023-11-01 13:12 UTC |
| `8.0-zts-alpine` | 2022-11-17 09:42 UTC |
| `8.0-zts-alpine3.16` | 2023-11-21 05:40 UTC |
| `8.0-zts-alpine3.15` | 2023-05-26 10:08 UTC |
| `8.0-zts-alpine3.14` | 2022-05-23 09:48 UTC |
| `8.0-zts-bullseye` | 2023-11-23 05:48 UTC |
| `8.0-zts-buster` | 2023-11-22 06:11 UTC |
| `8.0-zts-debian` | 2023-11-01 13:11 UTC |
| `7.4-nts-alpine` | 2022-11-07 16:09 UTC |
| `7.4-nts-alpine3.16` | 2022-11-07 16:09 UTC |
| `7.4-nts-alpine3.15` | 2022-11-07 16:10 UTC |
| `7.4-nts-alpine3.14` | 2022-05-23 09:46 UTC |
| `7.4-nts-alpine3.13` | 2021-11-24 09:33 UTC |
| `7.4-nts-alpine3.12` | 2021-09-28 09:57 UTC |
| `7.4-nts-alpine3.11` | 2021-09-23 04:06 UTC |
| `7.4-nts-alpine3.10` | 2021-01-12 07:46 UTC |
| `7.4-nts-bullseye` | 2022-11-27 08:35 UTC |
| `7.4-nts-buster` | 2022-11-27 08:35 UTC |
| `7.4-nts-debian` | 2022-11-27 08:36 UTC |
| `7.4-zts-alpine` | 2022-11-17 09:41 UTC |
| `7.4-zts-alpine3.16` | 2022-11-17 09:41 UTC |
| `7.4-zts-alpine3.15` | 2022-11-17 09:41 UTC |
| `7.4-zts-alpine3.14` | 2022-05-23 09:46 UTC |
| `7.4-zts-alpine3.13` | 2021-11-24 09:40 UTC |
| `7.4-zts-alpine3.12` | 2021-09-28 09:59 UTC |
| `7.4-zts-alpine3.11` | 2021-09-23 04:09 UTC |
| `7.4-zts-alpine3.10` | 2021-01-12 07:46 UTC |
| `7.4-zts-bullseye` | 2022-11-27 08:36 UTC |
| `7.4-zts-buster` | 2022-11-27 08:36 UTC |
| `7.4-zts-debian` | 2022-11-27 08:35 UTC |
| `7.3-nts-alpine` | 2021-11-24 09:33 UTC |
| `7.3-nts-alpine3.14` | 2021-11-29 08:05 UTC |
| `7.3-nts-alpine3.13` | 2021-11-24 09:33 UTC |
| `7.3-nts-alpine3.12` | 2021-09-28 09:57 UTC |
| `7.3-nts-alpine3.11` | 2021-09-23 04:06 UTC |
| `7.3-nts-alpine3.10` | 2021-01-12 07:46 UTC |
| `7.3-nts-alpine3.9` | 2019-07-11 03:28 UTC |
| `7.3-nts-alpine3.8` | 2019-05-18 16:25 UTC |
| `7.3-nts-bullseye` | 2021-11-29 08:05 UTC |
| `7.3-nts-buster` | 2021-11-29 08:05 UTC |
| `7.3-nts-debian` | 2021-11-29 08:05 UTC |
| `7.3-nts-stretch` | 2021-11-29 08:06 UTC |
| `7.3-zts-alpine` | 2021-11-24 09:40 UTC |
| `7.3-zts-alpine3.14` | 2021-11-29 08:05 UTC |
| `7.3-zts-alpine3.13` | 2021-11-24 09:40 UTC |
| `7.3-zts-alpine3.12` | 2021-09-28 09:58 UTC |
| `7.3-zts-alpine3.11` | 2021-09-23 04:08 UTC |
| `7.3-zts-alpine3.10` | 2021-01-12 07:46 UTC |
| `7.3-zts-alpine3.9` | 2019-07-11 03:28 UTC |
| `7.3-zts-alpine3.8` | 2019-05-18 16:25 UTC |
| `7.3-zts-bullseye` | 2021-11-29 08:06 UTC |
| `7.3-zts-buster` | 2021-11-29 08:06 UTC |
| `7.3-zts-debian` | 2021-11-29 08:06 UTC |
| `7.3-zts-stretch` | 2021-11-29 08:05 UTC |
| `7.2-nts-alpine3.12` | 2020-11-29 18:39 UTC |
| `7.2-nts-alpine3.11` | 2020-11-29 18:39 UTC |
| `7.2-nts-alpine3.10` | 2020-11-29 18:39 UTC |
| `7.2-nts-buster` | 2020-11-29 18:40 UTC |
| `7.2-nts-stretch` | 2020-11-29 18:40 UTC |
| `7.2-zts-alpine3.12` | 2020-11-29 18:40 UTC |
| `7.2-zts-alpine3.11` | 2020-11-29 18:40 UTC |
| `7.2-zts-alpine3.10` | 2020-11-29 18:40 UTC |
| `7.2-zts-buster` | 2020-11-29 18:42 UTC |
| `7.2-zts-stretch` | 2020-11-29 18:42 UTC |

<!-- tag-last-pushed-table-end -->

# Credits

This project is based on [Usabilla](https://usabilla.com/)'s [PHP Docker Template](https://github.com/usabilla/php-docker-template).
Lots of the documentation on that repository also applies here, with the big difference that this project only 
supplies CLI images.
