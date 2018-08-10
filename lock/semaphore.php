<?php
include_once "../common.php";

function lock()
{
    $redis = getRedis();
    $redis->zInter()
}