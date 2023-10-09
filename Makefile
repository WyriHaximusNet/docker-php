qa: lint build test scan-vulnerability
build: clean-tags build-all
push: build push

mkfile_path := $(abspath $(lastword $(MAKEFILE_LIST)))
current_dir := $(abspath $(patsubst %/,%,$(dir $(mkfile_path))))

.PHONY: *

BUILDINGIMAGE=*

.NOTPARALLEL: clean-tags
clean-tags:
	rm ${current_dir}/docker-image/build.tags || true

lint:
	docker run -v ${current_dir}:/project:ro --workdir=/project --rm -it hadolint/hadolint:latest-debian hadolint /project/Dockerfile-*

build-all:
	PHP=$(shell docker run --rm wyrihaximusgithubactions/supported-php-versions:v1 | php -r 'echo explode("::set-output name=versions::", stream_get_contents(STDIN))[1];') \
	ALPINE=$(shell docker run --rm wyrihaximusgithubactions/supported-alpine-linux-versions:v1 | php -r 'echo explode("::set-output name=versions::", stream_get_contents(STDIN))[1];') \
	php utils/all-images.php  | \
	php -r 'echo explode("::set-output name=image::", stream_get_contents(STDIN))[1];' | jq -r '.[]' | \
	tr '-' ' ' | \
	xargs -I {} -t bash -c './build-php.sh {}'


test: test-cli test-fpm test-http

test-nts: ./docker-image/image.tags
	IMAGE_ARCH=$(IMAGE_ARCH) xargs -I % ./test-nts.sh % < ./docker-image/image.tags

test-zts: ./docker-image/image.tags
	IMAGE_ARCH=$(IMAGE_ARCH) xargs -I % ./test-zts.sh % < ./docker-image/image.tags

scan-vulnerability:
	cat ./docker-image/image.tags | xargs -I % sh -c 'docker run -v /tmp/trivy:/var/lib/trivy -v /var/run/docker.sock:/var/run/docker.sock -t aquasec/trivy:latest --cache-dir /var/lib/trivy image --exit-code 1 --no-progress --format table % || echo "% is vulnerable"'

ci-scan-vulnerability:
	cat ./docker-image/image.tags | xargs -I % sh -c 'docker run -v /tmp/trivy:/var/lib/trivy -v /var/run/docker.sock:/var/run/docker.sock -t aquasec/trivy:latest --cache-dir /var/lib/trivy image --exit-code 1 --no-progress --format table %'; \
	XARGS_EXIT=$$?; \
	exit $${XARGS_EXIT}
