<?php

namespace App\Services\Pppoe;

use App\Models\Router;
use App\Services\Mikrotik\MikrotikService;

class PppoeAggregatorService
{
    public function active(): array
    {
        $results = [];

        $routers = Router::all();

        foreach ($routers as $router) {

            try {
                $mikrotik = new MikrotikService($router);

                $actives = $mikrotik->get('/ppp/active');

                foreach ($actives as $user) {

                    $results[] = [
                        'router_id'   => $router->id,
                        'router_name' => $router->name,

                        'name'    => $user['name'] ?? null,
                        'service' => $user['service'] ?? null,
                        'address' => $user['address'] ?? null,
                        'uptime'  => $user['uptime'] ?? null,
                        'caller'  => $user['caller-id'] ?? null,
                    ];
                }

            } catch (\Throwable $e) {
                // optional: log error
            }
        }

        return $results;
    }

    public function secrets(): array
    {
        $results = [];

        $routers = Router::all();

        foreach ($routers as $router) {

            try {
                $mikrotik = new MikrotikService($router);

                $secrets = $mikrotik->get('/ppp/secret');

                foreach ($secrets as $secret) {

                    $results[] = [
                        'router_id'   => $router->id,
                        'router_name' => $router->name,

                        'name'     => $secret['name'] ?? null,
                        'profile'  => $secret['profile'] ?? null,
                        'service'  => $secret['service'] ?? null,
                        'disabled' => $secret['disabled'] ?? null,
                        'comment'  => $secret['comment'] ?? null,
                    ];
                }

            } catch (\Throwable $e) {
                // optional: log error
            }
        }

        return $results;
    }
}