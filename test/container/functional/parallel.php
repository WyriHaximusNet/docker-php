<?php

$runtime = new \parallel\Runtime();

$future = $runtime->run(function(){
    for ($i = 0; $i < 500; $i++)
        echo "*";

    return 0;
});

for ($i = 0; $i < 500; $i++) {
    echo ".";
}

exit($future->value());
