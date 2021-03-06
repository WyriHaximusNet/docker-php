<?php

$upstreamImages = [];
foreach (range(1, 10) as $i) {
    $upstreamJson = json_decode(file_get_contents('https://hub.docker.com/v2/repositories/library/php/tags?page_size=100&page=' . $i), true);
    foreach ($upstreamJson['results'] as $image) {
        $upstreamImages[$image['name']] = new DateTimeImmutable($image['last_updated']);
    }
}

$images = [];
foreach (range(1, 10) as $i) {
    $json = json_decode(file_get_contents('https://hub.docker.com/v2/repositories/wyrihaximusnet/php/tags?page_size=100&page=' . $i), true);
    foreach ($json['results'] as $image) {
        $images[$image['name']] = new DateTimeImmutable($image['last_updated']);
    }
}

$line = [];
foreach (json_decode(getenv('ALPINE'), true) as $alpine) {
    foreach (json_decode(getenv('PHP'), true) as $php) {
        if (array_key_exists($php . '-zts-alpine' . $alpine, $upstreamImages)) {
            $line[] = 'zts-zts-' . $php . '-alpine-alpine' . $alpine . '-alpine3.11';
        }
        if (array_key_exists($php . '-cli-alpine' . $alpine, $upstreamImages)) {
            $line[] = 'cli-nts-' . $php . '-alpine-alpine' . $alpine . '-alpine3.11';
        }
    }
}

foreach (json_decode(getenv('DEBIAN'), true) as $debian) {
    foreach (json_decode(getenv('PHP'), true) as $php) {
        if (array_key_exists($php . '-zts-' . $debian, $upstreamImages)) {
            $line[] = 'zts-zts-' . $php . '-debian-' . $debian . '-buster';
        }
        if (array_key_exists($php . '-cli-' . $debian, $upstreamImages)) {
            $line[] = 'cli-nts-' . $php . '-debian-' . $debian . '-buster';
        }
    }
}

echo 'Found the following images to build: ', PHP_EOL, '- ', implode(PHP_EOL . '- ', $line), PHP_EOL;
echo '::set-output name=image::', json_encode($line), PHP_EOL;;
