<?php

function getRedis()
{
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    return $redis;
}

function acquireLock($lockName, $timeout=10)
{

}

acquireLock('');