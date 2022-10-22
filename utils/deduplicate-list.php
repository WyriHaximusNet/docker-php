<?php

echo json_encode(array_values(array_unique(array_map(static fn (string $tag): string => trim($tag), file('images-that-need-updating.list')))));
