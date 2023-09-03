<?php

foreach (array_unique(
    array_map(
        static fn (string $tag): string => str_replace(
            array_map(
                static fn (string $tag): string => '-' . $tag,
                json_decode(getenv("TARGET_ARCHS")),
            ),
            '',
            $tag,
        ),
        file('tags-to-push.list')
    )
) as $image) {
    file_put_contents("./command.sh", "\ndocker manifest create \"" . $argv[1] . "/" . trim($image) . "\"", \FILE_APPEND);

    foreach (json_decode(getenv("TARGET_ARCHS"), true) as $arch) {
        file_put_contents("./command.sh", " --amend " . $argv[1] . "/" . trim($image) . "-" . $arch, \FILE_APPEND);
    }
    file_put_contents("./command.sh", "\ndocker manifest push \"" . $argv[1] . "/" . trim($image) . "\"\n", \FILE_APPEND);
}
