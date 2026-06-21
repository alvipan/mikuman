<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Router;
use App\Services\Hotspot\HotspotSyncService;

class SyncHotspot extends Command
{
    protected $signature = 'mikuman:sync-hotspot';

    protected $description = 'Sync hotspot events from MikroTik';

    public function handle(HotspotSyncService $service): int
    {
        $processed = 0;

        foreach (Router::all() as $router) {
            $processed += $service->sync($router);
        }

        $this->info("Processed: {$processed}");

        return self::SUCCESS;
    }
}