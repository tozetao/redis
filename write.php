<?php

//file_put_contents(__DIR__ . '/content.log', "12345\n", FILE_APPEND);


function record($data = null)
{
    if(!empty($data))
    {
        $date = date('Y-m-d H:i:s', time());
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