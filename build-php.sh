#!/bin/bash

set -eEuo pipefail

declare -r SRC_IMAGE=$1

declare -r DST_IMAGE=$2

declare -r VERSION_PHP=$3

declare -r VERSION_ALPINE=$4

# I could create a placeholder like php:x.y-image-alpinex.y in the Dockerfile itself,
# but I think it wouldn't be a good experience if you try to build the image yourself
# thus that's the way I opted to have dynamic base images
declare -r IMAGE_ORIGINAL_TAG="7.[0-9]-${SRC_IMAGE}-alpine3.11"

declare -r IMAGE_TAG="${VERSION_PHP}-${SRC_IMAGE}-alpine${VERSION_ALPINE}"
declare -r WYRIHAXIMUSNET_TAG="wyrihaximusnet/php:${VERSION_PHP}-${DST_IMAGE}-alpine${VERSION_ALPINE}"
declare -r WYRIHAXIMUSNET_TAG_DEV="${WYRIHAXIMUSNET_TAG}-dev"
declare -r WYRIHAXIMUSNET_TAG_ROOT="${WYRIHAXIMUSNET_TAG}-root"
declare -r WYRIHAXIMUSNET_TAG_DEV_ROOT="${WYRIHAXIMUSNET_TAG}-dev-root"

declare -r TAG_FILE="./docker-image/image.tags"

sed -E "s/${IMAGE_ORIGINAL_TAG}/${IMAGE_TAG}/g" "Dockerfile-${DST_IMAGE}" | docker build --no-cache --squash --pull --build-arg BUILD_DATE=`date -u +"%Y-%m-%dT%H:%M:%SZ"` --build-arg VCS_REF=`git rev-parse --short HEAD` -t "${WYRIHAXIMUSNET_TAG}" --target="${DST_IMAGE}" -f - . \
    && echo "$WYRIHAXIMUSNET_TAG" >> "$TAG_FILE"

sed -E "s/${IMAGE_ORIGINAL_TAG}/${IMAGE_TAG}/g" "Dockerfile-${DST_IMAGE}" | docker build --pull --squash --build-arg BUILD_DATE=`date -u +"%Y-%m-%dT%H:%M:%SZ"` --build-arg VCS_REF=`git rev-parse --short HEAD` -t "${WYRIHAXIMUSNET_TAG_DEV}" --target="${DST_IMAGE}-dev" -f - . \
    && echo "$WYRIHAXIMUSNET_TAG_DEV" >> "$TAG_FILE"

sed -E "s/${IMAGE_ORIGINAL_TAG}/${IMAGE_TAG}/g" "Dockerfile-${DST_IMAGE}" | docker build --pull --squash --build-arg BUILD_DATE=`date -u +"%Y-%m-%dT%H:%M:%SZ"` --build-arg VCS_REF=`git rev-parse --short HEAD` -t "${WYRIHAXIMUSNET_TAG_ROOT}" --target="${DST_IMAGE}-root" -f - . \
    && echo "$WYRIHAXIMUSNET_TAG_ROOT" >> "$TAG_FILE"

sed -E "s/${IMAGE_ORIGINAL_TAG}/${IMAGE_TAG}/g" "Dockerfile-${DST_IMAGE}" | docker build --pull --squash --build-arg BUILD_DATE=`date -u +"%Y-%m-%dT%H:%M:%SZ"` --build-arg VCS_REF=`git rev-parse --short HEAD` -t "${WYRIHAXIMUSNET_TAG_DEV_ROOT}" --target="${DST_IMAGE}-dev-root" -f - . \
    && echo "$WYRIHAXIMUSNET_TAG_DEV_ROOT" >> "$TAG_FILE"
