<?php
include './semaphore.php';

function test() {
    $counter = 1;

    while(($uuid = semaphoreLock('test', 1, 10)) === false) {
        echo "get uuid {$counter} \n";
        $counter++;
        sleep(1);
    }

    echo $uuid, "\n";
    releaseLock('test', $uuid);
}

test();