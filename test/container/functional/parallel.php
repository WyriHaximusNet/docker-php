<?php

if (PHP_VERSION_ID >= 80000) {
    exit(33);
}

$runtime = new \parallel\Runtime();

$future = $runtime->run(function(){
    for ($i = 0; $i < 500; $i++)
        echo "*";

    return 33;
});

for ($i = 0; $i < 500; $i++) {
    echo ".";
}

exit($future->value());
