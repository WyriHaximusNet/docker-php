<?php

eio_stat(__FILE__, \EIO_PRI_DEFAULT, function ($_, $data) {
    echo $data['size'];
});

eio_event_loop();
