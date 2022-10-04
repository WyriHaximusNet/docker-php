<?php

$config = new \EventConfig();
$loop = new \EventBase($config);

$i = 0;
$event = new \Event($loop, -1, \Event::TIMEOUT | \Event::PERSIST, function() use (&$i, &$event, $loop){
    echo $i;
    $i++;

    if ($i > 3) {
        $event->free();
    }
});
$event->add(1);

$loop->loop(\EventBase::LOOP_ONCE);
$loop->loop(\EventBase::LOOP_ONCE);
$loop->loop(\EventBase::LOOP_ONCE);
$loop->loop(\EventBase::LOOP_ONCE);
$loop->loop(\EventBase::LOOP_ONCE);
$loop->loop(\EventBase::LOOP_ONCE);

echo "finished";