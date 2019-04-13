<?php

$runtime = new \parallel\Runtime();

$future = $runtime->run(function(){
    for ($i = 0; $i < 500; $i++)
        echo "*";

    return $i;
});

for ($i = 0; $i < 500; $i++) {
    echo ".";
}

exit($future->value());
