<?php

$upstreamImages = [];
foreach (range(1, 5) as $i) {
    $upstreamJson = json_decode(file_get_contents('https://hub.docker.com/v2/repositories/library/php/tags?page_size=100&page=' . $i), true);
    foreach ($upstreamJson['results'] as $image) {
        $upstreamImages[$image['name']] = new DateTimeImmutable($image['last_updated']);
    }
}
$images = [];
$json = json_decode(file_get_contents('https://hub.docker.com/v2/repositories/wyrihaximusnet/php/tags?page_size=100'), true);
foreach ($json['results'] as $image) {
    $images[$image['name']] = new DateTimeImmutable($image['last_updated']);
}

$output = [];
foreach (json_decode(getenv('ALPINE'), true) as $alpine) {
    foreach (json_decode(getenv('PHP'), true) as $php) {
        $name = $php . '-zts-alpine' . $alpine;

        if (!array_key_exists($name, $upstreamImages) || !array_key_exists($name, $images)) {
            continue;
        }

        $output[] = ['alpine' => $alpine, 'php' => $php];
    }
}

$line = [];
foreach ($output as $image) {
    $line[] = 'zts-zts-' . $image['php'] . '-' . $image['alpine'];
    $line[] = 'cli-nts-' . $image['php'] . '-' . $image['alpine'];
}

echo 'Found the following images to build: ', implode(', ', $line), PHP_EOL;
echo '::set-output name=image::', json_encode($line), PHP_EOL;;
