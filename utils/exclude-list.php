<?php

// Long term this list needs to be empty, but a few things are getting in the way and ARM is the weakest link of the chain at the moment

$excludes = [];
$excludes[] = [
    'arch' => 'arm',
    'image' => 'cli-nts-8.1-8.1-alpine-alpine3.16-alpine3.16-alpine3.11',
];
$excludes[] = [
    'arch' => 'arm',
    'image' => 'cli-nts-8.1-8.1-alpine-alpine3.16-alpine-alpine3.11',
];
$excludes[] = [
    'arch' => 'arm',
    'image' => 'zts-zts-8.1-8.1-alpine-alpine3.16-alpine3.16-alpine3.11',
];
$excludes[] = [
    'arch' => 'arm',
    'image' => 'zts-zts-8.1-8.1-alpine-alpine3.16-alpine-alpine3.11',
];
$excludes[] = [
    'arch' => 'arm',
    'image' => 'zts-zts-8.1-8.1-alpine-alpine3.15-alpine3.15-alpine3.11',
];
$excludes[] = [
    'arch' => 'arm64',
    'image' => 'zts-zts-8.1-8.1-alpine-alpine3.15-alpine3.15-alpine3.11',
];
$excludes[] = [
    'arch' => 'amd64',
    'image' => 'zts-zts-8.1-8.1-alpine-alpine3.15-alpine3.15-alpine3.11',
];
$excludes[] = [
    'arch' => 'arm',
    'image' => 'zts-zts-8.1-8.1-alpine-alpine3.14-alpine3.14-alpine3.11',
];
$excludes[] = [
    'arch' => 'arm64',
    'image' => 'zts-zts-8.1-8.1-alpine-alpine3.14-alpine3.14-alpine3.11',
];
$excludes[] = [
    'arch' => 'amd64',
    'image' => 'zts-zts-8.1-8.1-alpine-alpine3.14-alpine3.14-alpine3.11',
];
$excludes[] = [
    'arch' => 'arm',
    'image' => 'zts-zts-8.1-8.1-alpine-alpine3.15-alpine-alpine3.11',
];
$excludes[] = [
    'arch' => 'arm64',
    'image' => 'zts-zts-8.1-8.1-alpine-alpine3.15-alpine-alpine3.11',
];
$excludes[] = [
    'arch' => 'amd64',
    'image' => 'zts-zts-8.1-8.1-alpine-alpine3.15-alpine-alpine3.11',
];
$excludes[] = [
    'arch' => 'arm',
    'image' => 'zts-zts-8.1-8.1-alpine-alpine3.15-alpine3.15-alpine3.11',
];
$excludes[] = [
    'arch' => 'arm',
    'image' => 'zts-zts-8.1-8.1-alpine-alpine3.15-alpine-alpine3.11',
];
$excludes[] = [
    'arch' => 'arm',
    'image' => 'zts-zts-8.1-8.1-alpine-alpine3.14-alpine3.14-alpine3.11',
];
$excludes[] = [
    'arch' => 'arm64',
    'image' => 'zts-zts-8.1-8.1-alpine-alpine3.14-alpine3.14-alpine3.11',
];
$excludes[] = [
    'arch' => 'amd64',
    'image' => 'zts-zts-8.1-8.1-alpine-alpine3.14-alpine3.14-alpine3.11',
];
$excludes[] = [
    'arch' => 'arm',
    'image' => 'cli-nts-8.1-8.1-alpine-alpine3.15-alpine-alpine3.11',
];
$excludes[] = [
    'arch' => 'arm',
    'image' => 'cli-nts-8.1-8.1-alpine-alpine3.15-alpine3.15-alpine3.11',
];
$excludes[] = [
    'arch' => 'arm',
    'image' => 'cli-nts-8.1-8.1-alpine-alpine3.15-alpine3.15-alpine3.11',
];
$excludes[] = [
    'arch' => 'arm',
    'image' => 'cli-nts-8.1-8.1-debian-buster-buster-buster',
];
$excludes[] = [
    'arch' => 'arm',
    'image' => 'cli-nts-8.1-8.1-debian-bullseye-bullseye-buster',
];
$excludes[] = [
    'arch' => 'arm',
    'image' => 'cli-nts-8.1-8.1-debian-buster-debian-buster',
];
$excludes[] = [
    'arch' => 'arm',
    'image' => 'zts-zts-8.1-8.1-debian-bullseye-bullseye-buster',
];
$excludes[] = [
    'arch' => 'arm',
    'image' => 'zts-zts-8.1-8.1-debian-buster-buster-buster',
];
$excludes[] = [
    'arch' => 'arm',
    'image' => 'zts-zts-8.1-8.1-debian-buster-debian-buster',
];


echo 'Excludes: ', json_encode($excludes), PHP_EOL;
echo '::set-output name=exclude::', json_encode($excludes), PHP_EOL;;
