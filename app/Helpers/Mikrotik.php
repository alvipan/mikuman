<?php

namespace App\Helpers;

use App\Models\Router;
use App\Helpers\RouterosAPI;

class Mikrotik
{
    protected static $client;

    public static function connect(Router $router) 
    {
        try {
            self::$client = new RouterosAPI();
            self::$client->debug = false;
            self::$client->connect(
                $router->host,
                $router->user,
                $router->pass
            );
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function request($path, $conditions = array())
    {
        if (!session('router')) {
            return false;
        }

        $router = Router::firstWhere('host', session('router'));
        self::connect($router);

        try {
            $result = self::$client->comm($path, $conditions);
            return $result;
        } catch (\Throwable $e) {
            return false;
        } 
    }
}