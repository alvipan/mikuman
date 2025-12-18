<?php

namespace App\Helpers;

use App\Models\Router;
use App\Helpers\RouterosAPI;
use Illuminate\Support\Facades\Crypt;

class Mikrotik
{
    protected static $client;

    public static function connect(Router $router) 
    {
        self::$client = new RouterosAPI();
        self::$client->debug = false;
        return self::$client->connect(
            $router->host,
            $router->user,
            Crypt::decryptString($router->pass)
        );
    }

    public static function request($path, $conditions = array())
    {
        if (!session('router')) {
            return null;
        }

        $router = Router::firstWhere('host', session('router'));
        self::connect($router);

        try {
            $result = self::$client->comm($path, $conditions);
            return $result;
        } catch (\Throwable $e) {
            return null;
        } 
    }
}