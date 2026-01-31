<?php

namespace App\Helpers;

use App\Helpers\RouterosAPI;

class Mikrotik
{
    protected static $host = '192.168.200.1';
    protected static $user = 'ashvia';
    protected static $pass = '#1Ashvia';
    protected static $client;

    public static function connect() 
    {
        try {
            self::$client = new RouterosAPI();
            self::$client->debug = false;
            self::$client->connect(
                self::$host,
                self::$user,
                self::$pass
            );
            return true;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public static function request($path, $conditions = array())
    {
        self::connect();

        try {
            $result = self::$client->comm($path, $conditions);
            return $result;
        } catch (\Throwable $e) {
            return null;
        } 
    }
}