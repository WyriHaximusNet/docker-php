#!/bin/bash

set -eEuo pipefail

declare -r SRC_IMAGE=$1

declare -r DST_IMAGE=$2

declare -r VERSION_PHP=$3

declare -r VERSION_ALPINE=$4

# I could create a placeholder like php:x.y-image-alpinex.y in the Dockerfile itself,
# but I think it wouldn't be a good experience if you try to build the image yourself
# thus that's the way I opted to have dynamic base images
declare -r IMAGE_ORIGINAL_TAG="7.[0-9]-${SRC_IMAGE}-alpine3.[0-9]"

declare -r IMAGE_TAG="${VERSION_PHP}-${SRC_IMAGE}-alpine${VERSION_ALPINE}"
declare -r WYRIHAXIMUSNET_TAG="wyrihaximusnet/php:${VERSION_PHP}-${DST_IMAGE}-alpine${VERSION_ALPINE}"
declare -r WYRIHAXIMUSNET_TAG_DEV="${WYRIHAXIMUSNET_TAG}-dev"

declare -r TAG_FILE="./tmp/build-${DST_IMAGE}.tags"

sed -E "s/${IMAGE_ORIGINAL_TAG}/${IMAGE_TAG}/g" "Dockerfile-${DST_IMAGE}" | docker build --pull -t "${WYRIHAXIMUSNET_TAG}" --target="${DST_IMAGE}" -f - . \
    && echo "$WYRIHAXIMUSNET_TAG" >> "$TAG_FILE"

sed -E "s/${IMAGE_ORIGINAL_TAG}/${IMAGE_TAG}/g" "Dockerfile-${DST_IMAGE}" | docker build --pull -t "${WYRIHAXIMUSNET_TAG_DEV}" --target="${DST_IMAGE}-dev" -f - . \
    && echo "$WYRIHAXIMUSNET_TAG_DEV" >> "$TAG_FILE"