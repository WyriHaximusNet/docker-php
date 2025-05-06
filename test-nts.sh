#!/bin/bash
#
# A simple script to start a Docker container
# and run Testinfra in it
# Original script: https://gist.github.com/renatomefi/bbf44d4e8a2614b1390416c6189fbb8e
# Author: @renatomefi https://github.com/renatomefi
#

set -eEuo pipefail

# The first parameter is a Docker tag or image id
declare -r DOCKER_TAG="$1"

declare TEST_SUITE

TEST_SUITE="php_$IMAGE_ARCH"

if [[ $DOCKER_TAG == *"-dev"* && $IMAGE_BASE_VERSION != *"alpha"* && $IMAGE_BASE_VERSION != *"beta"* && $IMAGE_BASE_VERSION != *"rc"*  && $IMAGE_BASE_VERSION != *"ALPHA"* && $IMAGE_BASE_VERSION != *"BETA"* && $IMAGE_BASE_VERSION != *"RC"* ]]; then
    TEST_SUITE="php_nts or php_dev"
else
    TEST_SUITE="php_nts or php_no_dev and not php_dev"
fi

if [[ $DOCKER_TAG == *"-slim"* ]]; then
    TEST_SUITE="php_slim or php_slim_$IMAGE_ARCH or $TEST_SUITE"
else
    if [[ $IMAGE_BASE_VERSION != *"alpha"* && $IMAGE_BASE_VERSION != *"beta"* && $IMAGE_BASE_VERSION != *"rc"*  && $IMAGE_BASE_VERSION != *"ALPHA"* && $IMAGE_BASE_VERSION != *"BETA"* && $IMAGE_BASE_VERSION != *"RC"* ]]; then
        TEST_SUITE="$TEST_SUITE"
    else
        TEST_SUITE="php_not_slim and php_not_slim_$IMAGE_ARCH or $TEST_SUITE"
    fi
fi

if [[ $DOCKER_TAG == *"-root"* ]]; then
    TEST_SUITE="php_root or $TEST_SUITE"
else
    TEST_SUITE="php_app or $TEST_SUITE"
fi

printf "Starting a container for '%s'\\n" "$DOCKER_TAG"

DOCKER_CONTAINER=$(docker run --rm -v "$(pwd)/test:/tests" -t -d "$DOCKER_TAG" php)
readonly DOCKER_CONTAINER

# Let's register a trap function, if our tests fail, finish or the script gets
# interrupted, we'll still be able to remove the running container
function tearDown {
    docker rm -f "$DOCKER_CONTAINER" &>/dev/null &
}
trap tearDown EXIT TERM ERR

# Finally, run the tests!
echo "Running test suite: $TEST_SUITE"
docker run --rm -t \
    -v "$(pwd)/test:/tests" \
    -v "$(pwd)/tmp/test-results:/results" \
    -v /var/run/docker.sock:/var/run/docker.sock:ro \
    renatomefi/docker-testinfra:5 \
    -m "$TEST_SUITE" --junitxml="/results/php-nts-$DOCKER_TAG.xml" \
    --disable-pytest-warnings \
    --verbose --hosts="docker://$DOCKER_CONTAINER"
