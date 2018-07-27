<?php
require dirname(__DIR__) . '/common.php';

/**
 * 对一个key进行加锁操作.
 * 成功将会返回加锁的key所对应的值，即uuid；
 * 获取锁失败的话将会在过期时间内尝试获取加锁操作，直到时间过期或者加锁成功
 *
 * @param $lockName
 * @param int $timeout    锁的超时时间，同时也决定了程序获取锁阻塞的最大时间
 * @return bool|string
 */
function acquireLock($lockName, $timeout = 10)
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

        //睡眠0.001秒，1秒 = 1000000微秒
        usleep(1000);
    }

    return false;
}

/**
 * 释放一个锁
 * @param $lockName
 * @param $uuid
 */
function releaseLock($lockName, $uuid)
{
    //监视加锁的key，取出加锁key的值与传递的值进行对比，如果相同则删除加锁的key并取消监视的key

    $redis = Singer::getInstance();
    $lockName = 'lock:' . $lockName;

    while(true)
    {
        $redis->watch($lockName);
        if($redis->get($lockName) == $uuid)
        {
            $redis->multi();
            $redis->delete($lockName);
            $redis->exec();
            return true;
        }
        $redis->unwatch();
        break;
    }

    return false;

//这里写个while循环不是很懂
//监视加锁的key是为了防止key的值被修改，导致误删除key。
}

//目前的实现，如果程序获得锁后奔溃，在没释放锁的情况下会发生死锁，锁一直被占用而没有释放，导致其他程序阻塞。

function testLock()
{
    //对某个资源进行加锁
    $rankKey = 'rank';
    $uuid = acquireLock($rankKey);

    if(!$uuid)
        exit('lock error!');
    else
        echo "get lock!\n";

    //执行一系列操作
    $redis = Singer::getInstance();
    $redis->multi()->set('address', 'shanghai')->exec();
    sleep(5);
    $result = releaseLock($rankKey, $uuid);
    var_dump($result);
}

$redis = Singer::getInstance();
$r = $redis->ttl('type');
var_dump($r);

//var_dump($redis->get('lock:rank'));
//testLock();
