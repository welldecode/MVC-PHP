<?php

namespace app\library;

class Load
{
    public static $config;

    public static function load($index)
    {
        static::$config = require  dirname(__FILE__, 3). '\\app\\config\\config.php';

        if (!isset(static::$config[$index])) {
            throw new \Exception("Esse índice não existe: {$index}, no classe Load");
        }

        return (object) static::$config[$index];
    }
}
