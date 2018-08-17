<?php

class Singer
{
    private static $instance;

    public static function getInstance()
    {
        if(self::$instance == null)
        {
            $redis = new Redis();
            self::$instance = $redis;
            $redis->connect('127.0.0.1');
            return $redis;
        }

        return self::$instance;

    }
}