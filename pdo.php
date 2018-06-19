<?php

$handler = new PDO('mysql:host=localhost;dbname=test', 'root', 'root');

//查询
$selectSql = 'select nums from test';
$query = $handler->prepare($selectSql);
$query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);

file_put_contents(__DIR__ . '/my.log', time() . "\n", FILE_APPEND);

//检查库存是否大于0
if($row['nums'] > 0)
{
    $updateSql = "update test set nums=nums-1";
    $query = $handler->prepare($updateSql);
    $result = $query->execute();

}

//var_dump($result);
//else
//{
//    exit('out of stock.');
//}








//$sql = "INSERT INTO test (id, nums) VALUES (:id, :nums)";
//$result = $query->execute(array(
//    ':id' => 1,
//    ':nums' => 100
//));
