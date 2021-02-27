#!/bin/bash

set -eEuo pipefail

declare -r SRC_IMAGE=$1

declare -r DST_IMAGE=$2

declare -r VERSION_PHP=$3

declare -r OS=$4

declare -r VERSION_OS=$5

declare -r VERSION_OS_FROM=$6

# I could create a placeholder like php:x.y-image-alpinex.y in the Dockerfile itself,
# but I think it wouldn't be a good experience if you try to build the image yourself
# thus that's the way I opted to have dynamic base images
declare -r IMAGE_ORIGINAL_TAG="7.[0-9]-${SRC_IMAGE}-${VERSION_OS_FROM}"

declare -r IMAGE_TAG="${VERSION_PHP}-${SRC_IMAGE}-${VERSION_OS}"
declare -r WYRIHAXIMUSNET_TAG="wyrihaximusnet/php:${VERSION_PHP}-${DST_IMAGE}-${VERSION_OS}"
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

for buildTarget in "${target[@]}"
do
  sed -E "s/${IMAGE_ORIGINAL_TAG}/${IMAGE_TAG}/g" "Dockerfile-${DST_IMAGE}-${OS}" | docker build --label org.label-schema.build-date=`date -u +"%Y-%m-%dT%H:%M:%SZ"` --label org.label-schema.vcs-ref=`git rev-parse --short HEAD` -t "${WYRIHAXIMUSNET_TAG}${buildTarget}" --target="${DST_IMAGE}${buildTarget}" -f - .
  echo "${WYRIHAXIMUSNET_TAG}${buildTarget}" >> "$TAG_FILE"
done
