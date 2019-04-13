<?php

$runtimeA = new \parallel\Runtime();
$runtimeB = new \parallel\Runtime();

$futureA = $runtimeA->run(function(){
    for ($i = 0; $i < 1024; $i++) {
        echo "*";
    }

    echo "|||";

    return $i;
});

$futureB = $runtimeB->run(function(){
    for ($i = 0; $i < 1024; $i++) {
        echo "$";
    }

    echo "{}{}{}";

    return $i;
});

do {
    echo ".";
} while (!$futureA->done() || !$futureB->done());

exit($futureA->value() + $futureB->value());
