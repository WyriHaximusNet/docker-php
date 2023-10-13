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
        static fn (string $tag): string => str_replace(
            array_map(
                static fn (string $tag): string => '-' . $tag,
                $archs
            ),
            '',
            $tag,
        ),
        file('tags-to-push.list')
    )
) as $image) {
    $dockerFilename = 'docker-file-' . md5($argv[1] . "/" . trim($image));
    file_put_contents($dockerFilename, 'FROM ' . $argv[1] . "/" . trim($image) . '-${TARGETARCH}');

    file_put_contents('./command.sh', "\ndocker buildx build -f " . $dockerFilename . " --platform=linux/" . implode(",linux/", $archs) . " -t \"" . $argv[1] . "/" . trim($image) . "\" --push .\n", \FILE_APPEND);
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
