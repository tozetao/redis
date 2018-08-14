<?php
include_once "../common.php";

//function acquireSemaphore($key, $limit, $timeout=10){}

function semaphoreLock($key, $limit, $timeout = 10)
{
    $key = 'lock:' . $key;
    $uuid = uniqid();
    $now = time();

    $redis = Singer::getInstance();
    $redis->multi();

    //移除过期的成员
    $redis->zremrangebyscore($key, '-inf', $now - $timeout);
    $redis->zadd($key, $now, $uuid,);
    $redis->zrank($key, $uuid);
    $result = $redis->exec();
    if($result && $result[2] < $limit)
        return $uuid;

    $redis->zrem($key, $uuid);
    return false;
}

function releaseLock($key, $uuid)
{
    $key = 'lock:' . $key;
    $redis = Singer::getInstance();
    return $redis->zRem($key, $uuid);
}

function test()
{
    $uuid = semaphoreLock('test', 1);

    var_dump($uuid);
    sleep(5);

    $r = releaseLock('test', $uuid);
    var_dump($r);
}


test();

/*
$obj = Singer::getInstance();
$obj->zAdd('r1', time(), 'z1');
$obj->zAdd('r1', time(), 'z2');

$r = $obj->zRem('r1', 'z1');
var_dump($r);

$r = $obj->zRem('r1', 'z2');
var_dump($r);

$r = $obj->zRem('r1', 'z3');
var_dump($r);
*/
