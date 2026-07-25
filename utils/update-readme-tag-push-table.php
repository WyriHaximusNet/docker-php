<?php

declare(strict_types=1);

const README = 'README.md';
const START = '<!-- tag-last-pushed-table-start -->';
const END = '<!-- tag-last-pushed-table-end -->';

$isBaseTag = static fn (string $tag): bool => !preg_match('/-(dev|slim|root)(-|$)/', $tag)
    && preg_match('/^\d+\.\d+-(nts|zts)-(alpine[\d.]*|[a-z]+)$/', $tag) === 1;

$readme = file_get_contents(README);
preg_match('/' . preg_quote(START) . '(.*?)' . preg_quote(END) . '/s', $readme, $match) || (fwrite(STDERR, "Markers not found in README.md\n") ?: exit(1));

$existing = [];
foreach (explode(PHP_EOL, trim($match[1])) as $line) {
    if (preg_match('/^\| `([^`]+)` \| (.+) \|$/', trim($line), $row) && $row[1] !== '*Pending first CI run*' && $isBaseTag($row[1])) {
        $existing[$row[1]] = $row[2];
    }
}

$hub = [];
for ($page = 1; $page <= 50; $page++) {
    $json = json_decode(file_get_contents('https://hub.docker.com/v2/repositories/wyrihaximusnet/php/tags?page_size=100&page=' . $page), true);
    if (!is_array($json) || count($json['results']) === 0) {
        break;
    }
    foreach ($json['results'] as $image) {
        if ($isBaseTag($image['name'])) {
            $hub[$image['name']] = new DateTimeImmutable($image['tag_last_pushed'] ?? $image['last_updated']);
        }
    }
    if ($json['next'] === null) {
        break;
    }
}

$matrixTags = [];
foreach (json_decode(getenv('IMAGE_MATRIX') ?: '[]', true) ?: [] as $image) {
    [, $type, , $php, , $os] = explode('-', $image);
    $matrixTags[] = $php . '-' . $type . '-' . $os;
}

$tags = array_values(array_filter(array_unique([...array_keys($existing), ...$matrixTags, ...array_keys($hub)]), $isBaseTag));
usort($tags, static function (string $a, string $b): int {
    preg_match('/^(\d+\.\d+)-(nts|zts)-(.+)$/', $a, $ma);
    preg_match('/^(\d+\.\d+)-(nts|zts)-(.+)$/', $b, $mb);
    if ($c = version_compare($mb[1] ?? '0.0', $ma[1] ?? '0.0')) {
        return $c;
    }
    if ($c = strcmp($ma[2] ?? '', $mb[2] ?? '')) {
        return $c;
    }
    $alpineVer = static fn (string $os): string => $os === 'alpine' ? '999.999' : (preg_match('/^alpine([\d.]+)$/', $os, $m) ? $m[1] : '0.0');
    $aAlpine = str_starts_with($ma[3], 'alpine');
    $bAlpine = str_starts_with($mb[3], 'alpine');
    if ($aAlpine && $bAlpine) {
        return ($c = version_compare($alpineVer($mb[3]), $alpineVer($ma[3]))) ? $c : strcmp($ma[3], $mb[3]);
    }

    return $aAlpine !== $bAlpine ? ($aAlpine ? -1 : 1) : strcmp($ma[3], $mb[3]);
});

$rows = ['| Tag | Last pushed (UTC) |', '|-----|-------------------|'];
foreach ($tags as $tag) {
    $when = isset($hub[$tag])
        ? $hub[$tag]->format('Y-m-d H:i') . ' UTC'
        : ($existing[$tag] ?? 'Not yet pushed');
    $rows[] = '| `' . $tag . '` | ' . $when . ' |';
}

$table = implode(PHP_EOL, $rows);
file_put_contents(README, preg_replace('/' . preg_quote(START) . '.*?' . preg_quote(END) . '/s', START . PHP_EOL . PHP_EOL . $table . PHP_EOL . PHP_EOL . END, $readme, 1));
