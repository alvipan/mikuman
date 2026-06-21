<?php

namespace App\Services\Router;

use App\Models\Router;
use App\Services\Mikrotik\MikrotikService;

class RouterService
{
    public const STATUS_READY = 'ready';
    public const STATUS_PARTIAL = 'partial';
    public const STATUS_NOT_SETUP = 'not-setup';
    public const STATUS_OFFLINE = 'offline';

    public const HOTSPOT_SCHEDULER = 'MIKUMAN-HOTSPOT-EXPIRE';
    public const PPPOE_SCHEDULER = 'MIKUMAN-PPPOE-EXPIRE';

    public function status(Router $router): string
    {
        try {

            $schedulers = $this->schedulers($router);

            if (! is_array($schedulers) || isset($schedulers['success'])) {
                return self::STATUS_OFFLINE;
            }

            $hotspot = $this->schedulerExists(
                $schedulers,
                self::HOTSPOT_SCHEDULER
            );

            $pppoe = $this->schedulerExists(
                $schedulers,
                self::PPPOE_SCHEDULER
            );

            return match (true) {
                $hotspot && $pppoe => self::STATUS_READY,
                $hotspot || $pppoe => self::STATUS_PARTIAL,
                default => self::STATUS_NOT_SETUP,
            };

        } catch (\Throwable $e) {
            return self::STATUS_OFFLINE;
        }
    }

    public function setup(Router $router): bool
    {
        $schedulers = $this->schedulers($router);

        if (! is_array($schedulers) || isset($schedulers['success'])) {
            throw new \RuntimeException('Router not reachable');
        }

        $hotspot = $this->schedulerExists(
            $schedulers,
            self::HOTSPOT_SCHEDULER
        );

        $pppoe = $this->schedulerExists(
            $schedulers,
            self::PPPOE_SCHEDULER
        );

        if ($hotspot && $pppoe) {
            return false;
        }

        $this->mikrotik($router)
            ->ensureEnvironment();

        return true;
    }

    public function stats(): array
    {
        $routers = Router::all();

        $stats = [
            'total' => $routers->count(),
            'ready' => 0,
            'partial' => 0,
            'not_setup' => 0,
            'offline' => 0,
        ];

        foreach ($routers as $router) {

            $status = $this->status($router);

            match ($status) {
                self::STATUS_READY => $stats['ready']++,
                self::STATUS_PARTIAL => $stats['partial']++,
                self::STATUS_NOT_SETUP => $stats['not_setup']++,
                self::STATUS_OFFLINE => $stats['offline']++,
            };
        }

        return $stats;
    }

    protected function mikrotik(Router $router): MikrotikService
    {
        return app(MikrotikService::class, [
            'router' => $router,
        ]);
    }

    protected function schedulers(Router $router): array
    {
        return $this->mikrotik($router)
            ->get('/system/scheduler');
    }

    protected function schedulerExists(
        array $schedulers,
        string $name
    ): bool {
        return collect($schedulers)
            ->contains(
                fn (array $item) => ($item['name'] ?? null) === $name
            );
    }
}