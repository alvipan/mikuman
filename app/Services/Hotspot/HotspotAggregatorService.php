<?php

namespace App\Services\Hotspot;

use App\Models\Router;

class HotspotAggregatorService
{
    public function active(): array
    {
        $results = [];

        $routers = Router::all();

        foreach ($routers as $router) {
            try {
                $service = new HotspotUserService($router);

                $actives = $service->active();

                foreach ($actives as $user) {
                    $results[] = [
                        'router_id' => $router->id,
                        'router_name' => $router->name,

                        'user' => $user['user'] ?? null,
                        'ip' => $user['address'] ?? null,
                        'mac' => $user['mac-address'] ?? null,
                        'uptime' => $user['uptime'] ?? null,
                    ];
                }

            } catch (\Throwable $e) {
                // optional: log error router
            }
        }

        return $results;
    }
}