<?php

foreach (file('/tmp/tags-to-push.list') as $tag) {
    $tag = trim($tag);
    if (strlen($tag) === 0) {
        continue;
    }
    foreach (json_decode(getenv('DOCKER_IMAGE_REGISTRIES_SECRET_MAPPING'), true) as $registry => $_) {
        file_put_contents(
            'build-and-sleep-' . $registry . '-' . $tag . '.sh',
            'docker buildx build -f docker-file-' . $tag . ' -t ' . $registry . '/' . getenv('DOCKER_IMAGE') . ':' . $tag . ' --platform=' . getenv('PLATFORM') . ' --push  .'. PHP_EOL . 'sleep 13' . PHP_EOL,
        );
    }
}
