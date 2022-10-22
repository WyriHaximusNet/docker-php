<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'cleaned-up-version.php';
$upstreamImages = [];
foreach (range(1, 50) as $i) {
    $upstreamJson = json_decode(file_get_contents('https://hub.docker.com/v2/repositories/library/php/tags?page_size=100&page=' . $i), true);
    foreach ($upstreamJson['results'] as $image) {
        $upstreamImages[$image['name']] = new DateTimeImmutable($image['last_updated']);
    }
}

$images = [];
foreach (range(1, 20) as $i) {
    $json = json_decode(file_get_contents('https://hub.docker.com/v2/repositories/wyrihaximusnet/php/tags?page_size=100&page=' . $i), true);
    foreach ($json['results'] as $image) {
        $images[$image['name']] = new DateTimeImmutable($image['last_updated']);
    }
}


$list = [];
$fullImageList = json_decode(file_get_contents('all-images.list'), true);
foreach ($fullImageList as $image) {
    [$sourceType, $destinatoinType, $destinationPhpVersoin, $sourcePhpVersion, $osType, $osDestinationName, $osDestinationName, $osSource] = explode('-', $image);
    $name = $destinationPhpVersoin . '-' . $destinatoinType . '-' . $osDestinationName;
    if (!array_key_exists($name, $images)) {
        continue;
    }
    if (!array_key_exists($name, $upstreamImages)) {
        continue;
    }
    if ($upstreamImages[$name] > $images[$name]) {
        echo $image, PHP_EOL;
    }
}
