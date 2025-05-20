<?php

namespace App\Helpers;

use App\Models\Router;
use RouterOS\Client;
use RouterOS\Query;

class Mikrotik
{
    public static function connect($routerId) {

        $router = Router::find($routerId);

        if ($router === null) {
            return false;
        }

        $client = new Client([
            'host' => $router->host,
            'user' => $router->user,
            'pass' => $router->pass,
            'port' => 8728,
        ]);

        return true;
    }

    private function request($path, $q = array()) {
        $query = new Query($path);

        foreach($q as $key => $val) {
            $query->where($key, $val);
        }
        return $this->client->query($query)->read();
    }
}