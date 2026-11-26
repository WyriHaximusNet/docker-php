<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'cleaned-up-version.php';

function findUpstreamVariant(string $php, array $variants, string $os, array $upstreamImages): ?string
{
    foreach ($variants as $variant) {
        if (array_key_exists($php . '-' . $variant . '-' . $os, $upstreamImages)) {
            return $variant;
        }
    }

    return null;
}

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
foreach (json_decode(getenv('PHP'), true) as $php) {
    if ($php === '8.1') {
        continue;
    }
    $php86Plus = version_compare($php, '8.6', '>=');
    $ztsVariants = $php86Plus ? ['zts', 'cli'] : ['zts'];
    $ntsVariants = $php86Plus ? ['nts'] : ['cli'];
    $latestOSVersion = '1.0';
    foreach (json_decode(getenv('ALPINE'), true) as $alpine) {
        if ($php === '8.1' && $alpine === '3.22') {
            continue;
        }
        if (version_compare($alpine, $latestOSVersion, ">=")) {
            $latestOSVersion = $alpine;
        }
        $ztsVariant = findUpstreamVariant($php, $ztsVariants, 'alpine' . $alpine, $upstreamImages);
        if ($ztsVariant !== null) {
            $line[] = $ztsVariant . '-zts-' . $php . '-' . cleanUpVersion($php) . '-alpine-alpine' . $alpine . '-alpine' . $alpine;
        }
        $ntsVariant = findUpstreamVariant($php, $ntsVariants, 'alpine' . $alpine, $upstreamImages);
        if ($ntsVariant !== null) {
            $line[] = $ntsVariant . '-nts-' . $php . '-' . cleanUpVersion($php) . '-alpine-alpine' . $alpine . '-alpine' . $alpine;
        }
    }

    $ztsVariant = findUpstreamVariant($php, $ztsVariants, 'alpine' . $latestOSVersion, $upstreamImages);
    if ($ztsVariant !== null) {
        $line[] = $ztsVariant . '-zts-' . $php . '-' . cleanUpVersion($php) . '-alpine-alpine' . $latestOSVersion . '-alpine';
    }
    $ntsVariant = findUpstreamVariant($php, $ntsVariants, 'alpine' . $latestOSVersion, $upstreamImages);
    if ($ntsVariant !== null) {
        $line[] = $ntsVariant . '-nts-' . $php . '-' . cleanUpVersion($php) . '-alpine-alpine' . $latestOSVersion . '-alpine';
    }
}

if (getenv('NO_DEBIAN') !== 'yes') {
    foreach (json_decode(getenv('PHP'), true) as $php) {
        $php86Plus = version_compare($php, '8.6', '>=');
        $ztsVariants = $php86Plus ? ['zts', 'cli'] : ['zts'];
        $ntsVariants = $php86Plus ? ['nts'] : ['cli'];
        foreach (json_decode(getenv('DEBIAN'), true) as $debian) {
            $ztsVariant = findUpstreamVariant($php, $ztsVariants, $debian, $upstreamImages);
            if ($ztsVariant !== null) {
                $line[] = $ztsVariant . '-zts-' . $php . '-' . cleanUpVersion($php) . '-debian-' . $debian . '-' . $debian . '';
                if ($debian === 'trixie') {
                    $line[] = $ztsVariant . '-zts-' . $php . '-' . cleanUpVersion($php) . '-debian-' . $debian . '-debian';
                }
            }
            $ntsVariant = findUpstreamVariant($php, $ntsVariants, $debian, $upstreamImages);
            if ($ntsVariant !== null) {
                $line[] = $ntsVariant . '-nts-' . $php . '-' . cleanUpVersion($php) . '-debian-' . $debian . '-' . $debian . '';
                if ($debian === 'trixie') {
                    $line[] = $ntsVariant . '-nts-' . $php . '-' . cleanUpVersion($php) . '-debian-' . $debian . '-debian';
                }
            }
        }
    }
}

echo 'Found the following images to build: ', PHP_EOL, '- ', implode(PHP_EOL . '- ', $line), PHP_EOL;
file_put_contents('all-images.list', json_encode($line));
