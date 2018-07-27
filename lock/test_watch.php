<?php

function getRedis()
{
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    return $redis;
}

function setName()
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

function changeName()
{
    $redis = getRedis();

    $redis = $redis->multi();
    $redis->set('name', 'hello world');
    $redis->get('name');
    $result = $redis->exec();
    var_dump($result);
}

$method = $argv[1];
$routes = ['setName', 'changeName'];

if(in_array($method, $routes))
{
    $method();
}