<?php

$versions = [];

$d = new DOMDocument();
@$d->loadHTML(file_get_contents('https://php.net/supported-versions')); // the variable $ads contains the HTML code above

foreach ((new DOMXPath($d))->query('//a') as $link) {
    $url = $link->getAttribute('href');

    if (strpos($url, '/downloads.php#v') === 0) {
        $versions[] = substr(
            $url,
            16,
            3
        );
    }
}

echo 'Found the following versions: ', implode(', ', $versions), PHP_EOL;
echo '::set-output name=php::', json_encode($versions), PHP_EOL;
