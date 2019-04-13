<?php

$runtimeA = new \parallel\Runtime();
$runtimeB = new \parallel\Runtime();

$futureA = $runtimeA->run(function(){
    for ($i = 0; $i < random_int(512, 1024); $i++) {
        echo "*";
    }

    echo "|||";

    return 0;
});

$futureB = $runtimeB->run(function(){
    for ($i = 0; $i < random_int(512, 1024); $i++) {
        echo "$";
    }

    echo "{}{}{}";

    return 0;
});

do {
    echo ".";
} while (!$futureA->done() || !$futureB->done());

exit($futureA->value() + $futureB->value());
