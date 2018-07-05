<?php

$redis = new Redis();
$redis->connect('127.0.0.1');
/*
echo '<pre/>';

$redis->setBit('tag123', 100, 1);
$result = $redis->getBit('tag123', 100);
var_dump($redis);

$result = $redis->setBit('tag123', 100, 0);
var_dump($result);
*/
/*
$handler = new PDO('mysql:host=localhost;dbname=test', 'root', 'root');
$selectSql = 'select user_id, tag_id from user_tag';
$query = $handler->prepare($selectSql);
$query->execute();

echo '<pre/>';

while($row = $query->fetch(PDO::FETCH_ASSOC))
{
    $key = 'tag:' . $row['tag_id'];
    $result = $redis->setBit($key, $row['user_id'], 1);

    var_dump($key);
    var_dump($result);
    var_dump($row['user_id']);
    echo '<br/>';
}*/

$key1 = 'tag:1';
$key2 = 'tag:2';
$key3 = 'tag:3';
$key4 = 'tag:4';
$key5 = 'tag:5';
var_dump($redis->bitCount($key1));
var_dump($redis->bitCount($key2));
var_dump($redis->bitCount($key3));
var_dump($redis->bitCount($key4));
var_dump($redis->bitCount($key5));


/*
echo '<pre/>';
$row = $query->fetch(PDO::FETCH_ASSOC);
print_r($row);

$row = $query->fetch(PDO::FETCH_ASSOC);
print_r($row);

$row = $query->fetch(PDO::FETCH_ASSOC);
print_r($row);*/
