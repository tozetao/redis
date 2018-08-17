<?php
include './semaphore.php';

function test()
{
    $uuid = semaphoreLock('test', 1, 10);
    $counter = 1;

    echo "get uuid: {$uuid} \n";

    while($counter <= 20) {
        echo "sleep {$counter} \n";
        $counter++;
        sleep(1);
    }

//    releaseLock('test', $uuid);
}

test();