<?php

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

/*
//测试1：先读取再递减
$nums = $redis->get('nums');
if($nums > 1)
    $redis->decr('nums');

//这里代码是希望nums的值不是负数，然后在并发情况下会变成负数
//假设nums是10，请求数有500，并发数50，如果有50个请求同时拿到nums的值并且通过大于1的检测，那么意味着会进行50次自减，这时结果会出现负数
*/


//测试计数器，假设nums = 100，并发请求100次
//$nums = $redis->get('nums');
//$nums--;
//$redis->set('nums', $nums);
//并发情况下会读取到多个相同的结果，再赋值是就会发生错误。



