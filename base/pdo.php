<?php

$handler = new PDO('mysql:host=localhost;dbname=test', 'root', 'root');

//查询
$selectSql = 'select nums from goods';
$query = $handler->prepare($selectSql);
$query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);

//检查库存是否大于0
if($row['nums'] > 0)
{
    $updateSql = "update goods set nums=nums-1";
    $query = $handler->prepare($updateSql);
    $result = $query->execute();
}
