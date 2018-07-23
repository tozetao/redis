<?php

function getRedis()
{
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    return $redis;
}

function acquireLock($lockName, $timeout=10)
{
    $redis = getRedis();

    $redis->watch('name');
    $redis = $redis->multi();
    $redis->set('name', 'zhaobaobao');
    $redis->get('name');
    sleep(5);
    $result = $redis->exec();

    var_dump($result);
}

acquireLock('qwer');