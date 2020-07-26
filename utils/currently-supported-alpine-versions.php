<?php

$versions = [];

$d = new DOMDocument();
@$d->loadHTML(file_get_contents('https://wiki.alpinelinux.org/wiki/Alpine_Linux:Releases'));

/** @var DOMNode $row */
foreach ((new DOMXPath($d))->query('//tr') as $row) {
    if (trim($row->childNodes->item(1)->textContent) === 'Branch') {
        continue;
    }

    if (trim($row->childNodes->item(1)->textContent) === 'edge') {
        continue;
    }

    $version = substr(trim($row->childNodes->item(1)->textContent), 1);
    if (trim($row->childNodes->item(11)->textContent) === 'on request only') {
        continue;
    }

    $versions[] = $version;
}

echo 'Found the following versions: ', implode(', ', $versions), PHP_EOL;
echo '::set-output name=alpine::', json_encode($versions), PHP_EOL;
