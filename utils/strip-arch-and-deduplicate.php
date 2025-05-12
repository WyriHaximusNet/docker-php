<?php

echo implode(
    PHP_EOL,
    array_values(
        array_filter(
            array_unique(
                array_map(
                    static function (string $tag) use ($argv): string {
                        $tag = trim($tag);

                        foreach (explode(',', getenv('ARCHS')) as $platform) {
                            [$os, $arch] = explode('/', $platform);

                            $tag = str_replace('-' . $arch, '', $tag);
                            $tag = str_replace($argv[1] . ':', '', $tag);
                        }

                        return $tag;
                    },
                    file('/tmp/docker-image/image.tags'),
                )
            ),
            static fn (string $tag): bool => strlen($tag) > 0
        )
    )
);
