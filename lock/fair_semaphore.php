<?php
include 'lock.php';

function lock($key, $limit = 5, $expire = 10)
{
    $lock = acquireLock('counter', 10);

    if ($lock) {
        $timestampKey = 'lock:timestamp:' . $key;
        $countKey = 'lock:count:' . $key;

        $now = time();
        $uuid = uniqid();

        $redis = Singer::getInstance();

        // 信号量计数器
        $count = $redis->incr('count');

        $redis->multi();
        // 移除过期的信号量
        $redis->zRemRangeByScore($timestampKey, '-inf', time() - $expire);

        // 使用交集合并俩个集合
        $redis->zInter($countKey, [$timestampKey, $countKey], [0, 1]);

        // 添加时间戳、添加信号量的计数器
        $redis->zAdd($timestampKey, $now, $uuid);
        $redis->zAdd($countKey, $count, $uuid);
        $redis->zRank($countKey, $uuid);
        $result = $redis->exec();

        echo $result[4], "\n";

        // 判断信号量的排名是否超过限制
        if ($result[4] < $limit) {
            echo "lock success\n";
            releaseLock('counter', $lock);
            return $uuid;
        } else {
            releaseLock('counter', $lock);
            // 移除获取失败的信号量
            $redis->zRem($timestampKey, $uuid);
            $redis->zRem($countKey, $uuid);
        }
    }

    return false;
}

function release($key, $uuid) {
    $timestampKey = 'lock:timestamp:' . $key;
    $countKey = 'lock:count:' . $key;
    $redis = Singer::getInstance();
    return $redis->multi()->zRem($timestampKey, $uuid)->zRem($countKey, $uuid)->exec();
}

for($i=0; $i<10; $i++){
    $uuid = lock('fair');
    echo time(), ': ', $uuid, "\n";
}