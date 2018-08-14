<?php
include_once "../common.php";

function push($key, $data)
{
    $redis = getRedis();
    $redis->rPush($key, json_encode($data));
}

function pop($key, $expire = 10)
{
    $redis = getRedis();
    return $redis->blPop($key, $expire);
}

function handleQunue($key)
{
    while(true)
    {
        $data = pop($key);
        // handle $data.
        sleep(3);
    }
}

// 一般处理任务队列的是一个进程