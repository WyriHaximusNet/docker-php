<?php

$upstreamImages = [];
foreach (range(1, 10) as $i) {
    $upstreamJson = json_decode(file_get_contents('https://hub.docker.com/v2/repositories/library/php/tags?page_size=100&page=' . $i), true);
    foreach ($upstreamJson['results'] as $image) {
        $upstreamImages[$image['name']] = new DateTimeImmutable($image['last_updated']);
    }
}

/**
 * This feature is not available for Debian images, because Debian images ALWAYS ship with CVE's on board
 * Uncomment this when that is resolved
 */
//$images = [];
//foreach (range(1, 10) as $i) {
//    $json = json_decode(file_get_contents('https://hub.docker.com/v2/repositories/wyrihaximusnet/php/tags?page_size=100&page=' . $i), true);
//    foreach ($json['results'] as $image) {
//        $images[$image['name']] = new DateTimeImmutable($image['last_updated']);
//    }
//}

$list = [];
foreach (json_decode(getenv('PHP'), true) as $php) {
    $latestOSVersion = '1.0';
    foreach (json_decode(getenv('ALPINE'), true) as $alpine) {
        if (version_compare($alpine, $latestOSVersion, ">=")) {
            $latestOSVersion = $alpine;
        }
        if (array_key_exists($php . '-zts-alpine' . $alpine, $upstreamImages)) {
            $list[] = $php . '-zts-alpine' . $alpine;
        }
        if (array_key_exists($php . '-cli-alpine' . $alpine, $upstreamImages)) {
            $list[] = $php . '-nts-alpine' . $alpine;
        }
    }

    if (array_key_exists($php . '-zts-alpine' . $latestOSVersion, $upstreamImages)) {
        $list[] = $php . '-zts-alpine';
    }
    if (array_key_exists($php . '-cli-alpine' . $latestOSVersion, $upstreamImages)) {
        $list[] = $php . '-nts-alpine';
    }
}

/**
 * This feature is not available for Debian images, because Debian images ALWAYS ship with CVE's on board
 * Uncomment this when that is resolved
 */
//foreach (json_decode(getenv('DEBIAN'), true) as $debian) {
//    foreach (json_decode(getenv('PHP'), true) as $php) {
//        if (array_key_exists($php . '-zts-' . $debian, $upstreamImages)) {
//            $list[] = $php . '-zts-' . $debian;
//        }
//        if (array_key_exists($php . '-cli-' . $debian, $upstreamImages)) {
//            $list[] = $php . '-nts-' . $debian;
//        }
//    }
//}

foreach ($list as $tag) {
    foreach ([ "", "-dev", "-root", "-dev-root", "-slim", "-slim-dev", "-slim-root", "-slim-dev-root",] as $tagSuffix) {
        echo 'wyrihaximusnet/php:', $tag, $tagSuffix, PHP_EOL;
    }
}
