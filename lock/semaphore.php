<?php
include_once "../common.php";

//function acquireSemaphore($key, $limit, $timeout=10){}

function semaphoreLock($key, $limit, $timeout = 5)
{
    $key = 'lock:' . $key;
    $uuid = uniqid();
    $now = time();

    $redis = Singer::getInstance();
    $redis->multi();

    //移除过期的成员
    $redis->zremrangebyscore($key, '-inf', $now - $timeout);
    $redis->zadd($key, $now, $uuid);
    $redis->zrank($key, $uuid);
    $result = $redis->exec();
    if($result && $result[2] < $limit) {
        $content = time() . ': ' . $result[2];
        writeLog('./log1', $content);
        return $uuid;
    }

    $redis->zrem($key, $uuid);
    return 'false';
}

function releaseLock($key, $uuid)
{
    $key = 'lock:' . $key;
    $redis = Singer::getInstance();
    return $redis->zRem($key, $uuid);
}

function writeLog($filename, $data) {
    $content = $data . "\n";
    file_put_contents($filename, $content, FILE_APPEND);
}

// 1. 测试限制是否生效
// 事务会将多个redis操作作为一个单元来进行执行
// 限制最多2个信号量，测试是否能够限制成功
function test() {
    for($i =0; $i < 1000; $i++){
        $uuid = semaphoreLock('test', 2, 5);
        $content = time() . ': ' . $uuid;
        writeLog('./log', $content);

        //如果释放uuid，可以正常运行。
    }
}



// 2. 测试超时是否生效
// 设置过期时间为5秒，允许获取的最大信号量为2，每间隔10秒获取10次信号量，获取的信号量不释放，判断后续的请求是否能正常获取信号量
$count = 1;
$s = 1;
while($count < 10) {
    for($i = 0; $i < 10; $i++) {
        $uuid = semaphoreLock('test', 2, 5);
        $content = time() . ': ' . $uuid;
        writeLog('./log', $content);
    }

    while($s <= 10) {
        echo "sleep $s \n";
        $s++;
        sleep(1);
    }

    $s = 1;
    $count++;
}