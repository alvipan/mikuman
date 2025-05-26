<?php

namespace App\Helpers;

use App\Models\Router;
use RouterOS\Client;
use RouterOS\Query;

class Mikrotik
{
    protected static $client;

    public static function connect(Router $router) 
    {
        try {
            self::$client = new Client([
                'host' => $router->host,
                'user' => $router->user,
                'pass' => $router->pass,
                'port' => 8728,
            ]);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function request($path, $conditions = array(), $conditionType = 'where') 
    {
        if (!session('router')) {
            return;
        }

        $router = Router::where('host', session('router'))->first();
        self::connect($router);

        $query = new Query($path);
        foreach($conditions as $key => $val) {
            if ($key == 'operations') {
                $query->operations($val);
            } else {
                $query->{$conditionType}($key, $val);
            }
        }
        return self::$client->query($query)->read();
    }
}