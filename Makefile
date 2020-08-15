qa: lint lint-shell build test scan-vulnerability
build: clean-tags build-nts build-zts
push: build push
ci-push: ci-docker-login push-from-tags

mkfile_path := $(abspath $(lastword $(MAKEFILE_LIST)))
current_dir := $(abspath $(patsubst %/,%,$(dir $(mkfile_path))))

.PHONY: *

BUILDINGIMAGE=*

.NOTPARALLEL: clean-tags
clean-tags:
	rm ${current_dir}/docker-image/build.tags || true

# Docker images push
push-from-tags:
	cat ./docker-image/image.tags | xargs -I % docker push %

# CI dependencies
ci-docker-login:
	docker login --username $$DOCKER_USER --password $$DOCKER_PASSWORD

lint:
	docker run -v ${current_dir}:/project:ro --workdir=/project --rm -it hadolint/hadolint:latest-debian hadolint /project/Dockerfile-nts /project/Dockerfile-zts

test: test-cli test-fpm test-http

test-nts: ./docker-image/image.tags
	xargs -I % ./test-nts.sh % < ./docker-image/image.tags

test-zts: ./docker-image/image.tags
	xargs -I % ./test-zts.sh % < ./docker-image/image.tags

scan-vulnerability:
	docker-compose -f test/security/docker-compose.yml -p clair-ci up -d
	RETRIES=0 && while ! wget -T 10 -q -O /dev/null http://localhost:6060/v1/namespaces ; do sleep 1 ; echo -n "." ; if [ $${RETRIES} -eq 60 ] ; then echo " Timeout, aborting." ; exit 1 ; fi ; RETRIES=$$(($${RETRIES}+1)) ; done
	cat ./docker-image/build-*.tags | xargs -I % sh -c 'clair-scanner --ip 172.17.0.1 -r "./docker-imageclair/%.json" -l ./clair/clair.log % || echo "% is vulnerable"'
	docker-compose -f test/security/docker-compose.yml -p clair-ci down

ci-scan-vulnerability:
	docker-compose -f test/security/docker-compose.yml -p clair-ci up -d
	RETRIES=0 && while ! wget -T 10 -q -O /dev/null http://localhost:6060/v1/namespaces ; do sleep 1 ; echo -n "." ; if [ $${RETRIES} -eq 60 ] ; then echo " Timeout, aborting." ; exit 1 ; fi ; RETRIES=$$(($${RETRIES}+1)) ; done
	cat ./docker-image/build-*.tags | xargs -I % sh -c 'clair-scanner --ip 172.17.0.1 -r "./clair/%.json" -l ./clair/clair.log %'; \
	XARGS_EXIT=$$?; \
	if [ $${XARGS_EXIT} -eq 123 ]; then find ./clair/wyrihaximusnet -type f | sed 's/^/-Fjson=@/' | xargs -d'\n' curl -X POST ${WALLE_REPORT_URL} -F channel=team_oz -F buildUrl=https://circleci.com/gh/wyrihaximusnet/docker-php/${CIRCLE_BUILD_NUM}#artifacts/containers/0; else exit $${XARGS_EXIT}; fi