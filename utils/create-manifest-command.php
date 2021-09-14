<?php

file_put_contents("./command.sh", str_replace("-amd64", "", "\ndocker manifest create \"" . $argv[1] . "/" . $argv[2] . "\""), \FILE_APPEND);

foreach (json_decode(getenv("TARGET_ARCHS"), true) as $arch) {
    file_put_contents("./command.sh", str_replace("-amd64", "", " --amend " . $argv[1] . "/" . $argv[2] . "-") . $arch, \FILE_APPEND);
}
file_put_contents("./command.sh", str_replace("-amd64", "", "\ndocker manifest push \"" . $argv[1] . "/" . $argv[2] . "\"\n"), \FILE_APPEND);
