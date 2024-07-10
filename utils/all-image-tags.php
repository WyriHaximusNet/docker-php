<?php

$list = [];
$fullImageList = json_decode(file_get_contents('all-images.list'), true);
foreach ($fullImageList as $image) {
    [$sourceType, $destinatoinType, $sourcePhpVersion, $destinationPhpVersoin, $osType, $osDestinationName, $osSource] = explode('-', $image);
    foreach ([ "", "-dev", "-root", "-dev-root", "-slim", "-slim-dev", "-slim-root", "-slim-dev-root",] as $tagSuffix) {
        echo 'wyrihaximusnet/php:', $destinationPhpVersoin . '-' . $destinatoinType . '-' . $osDestinationName, $tagSuffix, '#', $image, PHP_EOL;
    }
}
