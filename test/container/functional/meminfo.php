<?php

const MEMINFO_DUMP_FILE = '/tmp/my_dump_file.json';

meminfo_dump(fopen(MEMINFO_DUMP_FILE, 'w'));

sleep(1);

if (!file_exists(MEMINFO_DUMP_FILE)) {
    exit(1);
}

$json = json_decode(file_get_contents(MEMINFO_DUMP_FILE), true);

if (!is_array($json)) {
    exit(1);
}

if (count($json) === 0) {
    exit(1);
}

echo 'meminfo';
