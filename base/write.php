<?php

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function record($data = null)
{
    if(!empty($data))
    {
//        $date = date('Y-m-d H:i:s', time());
        $date = microtime_float();
        file_put_contents('write.log', $date . '：' . $data . "\n", FILE_APPEND);
    }
}

$filename = './stock.txt';
$handle = fopen($filename, 'a+b');

if(!$handle)
    record('fopen error');

$store = fgets($handle, 1024);	//库存数量
record('current store: ' . $store);

if($store > 0)
{
    $result = ftruncate($handle, 0);	//清空文件内容
    if(!$result)
        record('ftruncate fail');

    $store--;
    record('--store: ' . $store);
    $result = fwrite($handle, $store);	//写入库存
    if(!$result)
        record('write fail');
}
fclose($handle);	//关闭