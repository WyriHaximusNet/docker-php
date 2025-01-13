#!/bin/bash

set -eEuo pipefail

declare -r SRC_IMAGE=$1

declare -r DST_IMAGE=$2

declare -r VERSION_PHP=$3

declare -r VERSION_PHP_ALIAS=$4

declare -r OS=$5

declare -r VERSION_OS=$6

declare -r VERSION_OS_TAG=$7

declare -r TARGET_ARCH=$8

declare -r IMAGE_TAG="${VERSION_PHP}-${SRC_IMAGE}-${VERSION_OS}"
declare -r WYRIHAXIMUSNET_TAG="wyrihaximusnet/php:${VERSION_PHP_ALIAS}-${DST_IMAGE}-${VERSION_OS_TAG}"
declare -r WYRIHAXIMUSNET_TAG_DEV="${WYRIHAXIMUSNET_TAG}-dev"
declare -r WYRIHAXIMUSNET_TAG_ROOT="${WYRIHAXIMUSNET_TAG}-root"
declare -r WYRIHAXIMUSNET_TAG_DEV_ROOT="${WYRIHAXIMUSNET_TAG}-dev-root"
declare -r WYRIHAXIMUSNET_TAG_SLIM="${WYRIHAXIMUSNET_TAG}-slim"
declare -r WYRIHAXIMUSNET_TAG_SLIM_DEV="${WYRIHAXIMUSNET_TAG}-slim-dev"
declare -r WYRIHAXIMUSNET_TAG_SLIM_ROOT="${WYRIHAXIMUSNET_TAG}-slim-root"
declare -r WYRIHAXIMUSNET_TAG_SLIM_DEV_ROOT="${WYRIHAXIMUSNET_TAG}-slim-dev-root"

declare -r TAG_FILE="./docker-image/image.tags"

declare -a target=(
  ""
  "-dev"
  "-root"
  "-dev-root"
  "-slim"
  "-slim-dev"
  "-slim-root"
  "-slim-dev-root"
)

docker pull "php:${IMAGE_TAG}"

for buildTarget in "${target[@]}"
do
  docker build \
    --build-arg ARCH=${TARGET_ARCH} \
    --build-arg PHP_VERSION=${VERSION_PHP} \
    --build-arg OS_VERSION=${VERSION_OS} \
    --platform ${TARGET_ARCH} \
    --label org.label-schema.build-date=`date -u +"%Y-%m-%dT%H:%M:%SZ"` \
    --label org.opencontainers.image.created=`date -u +"%Y-%m-%dT%H:%M:%SZ"` \
    --label org.label-schema.vcs-ref=`git rev-parse --short HEAD` \
    --label org.opencontainers.image.revision-ref=`git rev-parse --short HEAD` \
    -t "${WYRIHAXIMUSNET_TAG}${buildTarget}-${TARGET_ARCH}" \
    --target="${DST_IMAGE}${buildTarget}" \
    -f "Dockerfile-${DST_IMAGE}-${OS}" .
  echo "${WYRIHAXIMUSNET_TAG}${buildTarget}-${TARGET_ARCH}" >> "$TAG_FILE"
done
