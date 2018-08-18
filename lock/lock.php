<?php
require dirname(__DIR__) . '/common.php';

function acquireLock($lockName, $timeout = 30)
{
    $redis = Singer::getInstance();

    $start = time();
    $end   = $start + $timeout;
    $uuid = uniqid();

    $key = 'lock:' . $lockName;

    while($start <= $end)
    {
        if($redis->setnx($key, $uuid))
        {
            $timeout = ceil($timeout);
            $redis->expire($key, $timeout);
            return $uuid;
        }
        else if($redis->ttl($key) === -2)
            $redis->expire($key, $timeout);

        //睡眠0.01秒，1秒 = 1000000微秒
        usleep(10000);
    }

    return false;
}

function releaseLock($lockName, $uuid)
{
    $redis = Singer::getInstance();
    $lockName = 'lock:' . $lockName;

    $redis->watch($lockName);
    $values = $redis->multi()->get($lockName)->exec();

    if($values && $values[0] === $uuid)
    {
        $redis->delete($lockName);
        return true;
    }

    $redis->unwatch();
    return false;
}

function testLock()
{
    //对某个资源进行加锁
//    $rankKey = 'rank';
//    $uuid = acquireLock($rankKey);
//
//    if(!$uuid)
//        exit('lock error!');
//    else
//        echo "get lock!\n";
//
//    //执行一系列操作
//    $redis = Singer::getInstance();
//    $redis->multi()->set('address', 'shanghai')->exec();
//
//    sleep(5);
//    $result = releaseLock($rankKey, $uuid);
//    var_dump($result);
    for ($i=0; $i<10; $i++) {
        $uuid = acquireLock('d1');
        echo $uuid, "\n";
        releaseLock('d1', $uuid);
    }
}

//testLock();