<?php

$archs = json_decode(
    str_replace(
        '\\',
        '',
        getenv("TARGET_ARCHS"),
    ),
    true,
);

foreach (array_unique(
    array_map(
        static fn (string $tag): string => trim(
            str_replace(
                array_map(
                    static fn (string $tag): string => '-' . $tag,
                    $archs
                ),
                '',
                $tag,
            ),
        ),
        file('tags-to-push.list')
    )
) as $image) {
    $labels = [];
    $imageName = $argv[1] . "/" . trim($image);

    $jsonString = null;
    exec('docker inspect --format=\'{{json .Config.Labels}}\' ' . $imageName . '-' . $archs[0], $jsonString);
    $json = json_decode($jsonString[0], true);
    foreach ($json as $labelKey => $labelValue) {
        $labels[] = '--label ' . $labelKey . '="' . $labelValue . '"';
    }

    $dockerFilename = 'docker-file-' . md5($imageName);
    file_put_contents($dockerFilename, 'FROM ' . $imageName . '-${TARGETARCH}');

    file_put_contents('./command.sh', "\ndocker buildx build " . implode(' ', $labels) . " -f " . $dockerFilename . " --platform=linux/" . implode(",linux/", $archs) . " -t \"" . $imageName . "\" --push .\n", \FILE_APPEND);
}

file_put_contents(
    './command.sh',
    str_replace(
        'docker.io/',
        '',
        file_get_contents(
            './command.sh',
        ),
    ),
);
