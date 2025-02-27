<?php

echo json_encode(
    array_values(
        array_filter(
            array_unique(
                array_map(
                    static fn (string $tag): string => trim($tag),
                    file('images-that-need-updating.list')
                )
            ),
            static fn (string $tag): bool => strlen($tag) > 0
        )
    )
);
