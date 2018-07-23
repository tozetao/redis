<?php
require dirname(__DIR__) . '/common.php';

/**
 * 对一个key进行加锁操作.
 * 成功将会返回加锁的key所对应的值，即uuid；
 * 获取锁失败的话将会在过期时间内尝试获取加锁操作，直到时间过期或者加锁成功
 *
 * @param $lockName
 * @param int $timeout
 * @return bool|string
 */
function acquireLock($lockName, $timeout = 10)
{
    $redis = Singer::getInstance();

    $start = time();
    $end   = $start + $timeout;
    $uuid = uniqid();

    while($start <= $end)
    {
        if($redis->setnx('lock:' . $lockName, $uuid))
            return $uuid;

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
        $redis->multi();
        if($redis->get($lockName) == $uuid)
        {
            $redis->delete($lockName);
            $redis->exec();
            return true;
        }
        $redis->unwatch();
        break;
    }

    return false;
}