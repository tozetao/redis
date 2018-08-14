<?php
include_once "../common.php";

function lock()
{
    $redis = Singer::getInstance();
    var_dump($redis);

    $redis->multi()
        ->set('name', 'zhangsan')
        ->get('name');

    $redis->zAdd('rank', 2001.1, 'zhangsan');

    $result = $redis->exec();

    var_dump($result);
}

lock();