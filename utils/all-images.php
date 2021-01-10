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

$output = [];
foreach (json_decode(getenv('ALPINE'), true) as $alpine) {
    foreach (json_decode(getenv('PHP'), true) as $php) {
        $name = $php . '-zts-alpine' . $alpine;

        if (!array_key_exists($name, $upstreamImages)) {
            continue;
        }

        $output[] = ['os' => 'alpine', 'os_version' => 'alpine' . $alpine, 'os_version_from' => 'alpine3.11', 'php' => $php];
    }
}

foreach (json_decode(getenv('DEBIAN'), true) as $debian) {
    foreach (json_decode(getenv('PHP'), true) as $php) {
        $name = $php . '-zts-' . $debian;

        if (!array_key_exists($name, $upstreamImages)) {
            continue;
        }

        $output[] = ['os' => 'debian', 'os_version' => $debian, 'os_version_from' => 'buster', 'php' => $php];
    }
}

$line = [];
foreach ($output as $image) {
    $line[] = 'zts-zts-' . $image['php'] . '-' . $image['os'] . '-' . $image['os_version'] . '-' . $image['os_version_from'];
    $line[] = 'cli-nts-' . $image['php'] . '-' . $image['os'] . '-' . $image['os_version'] . '-' . $image['os_version_from'];
}

echo 'Found the following images to build: ', PHP_EOL, '- ', implode(PHP_EOL . '- ', $line), PHP_EOL;
echo '::set-output name=image::', json_encode($line), PHP_EOL;;
