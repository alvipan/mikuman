<?php

namespace App\Services\Hotspot;

use Carbon\Carbon;
use App\Models\Router;
use App\Models\HotspotUser;
use App\Models\ResellerCommission;
use App\Services\Mikrotik\MikrotikService;
use Illuminate\Support\Facades\DB;

class HotspotSyncService
{
    public function sync(Router $router): int
    {
        $mikrotik = new MikrotikService($router);

        $scripts = $mikrotik->get('/system/script');

        if (! is_array($scripts)) {
            return 0;
        }

        $processed = 0;

        foreach ($scripts as $script) {

            $comment = $script['comment'] ?? '';

            if (! str_starts_with($comment, 'VCR|')) {
                continue;
            }

            $processed += $this->processVoucherEvent(
                $mikrotik,
                $router,
                $script
            );
        }

        return $processed;
    }

    protected function processVoucherEvent(
        MikrotikService $mikrotik,
        Router $router,
        array $script
    ): int {

        $data = $this->parseEvent(
            $script['comment']
        );

        $voucher = HotspotUser::query()
            ->where('router_id', $router->id)
            ->where('username', $data['username'])
            ->first();

        if (! $voucher) {

            $this->deleteScript(
                $mikrotik,
                $script['.id']
            );

            return 0;
        }

        if ($voucher->used_at !== null) {

            $this->deleteScript(
                $mikrotik,
                $script['.id']
            );

            return 0;
        }

        DB::transaction(function () use ($voucher, $data) {

            $voucher->update([
                'used_at'    => $data['used_at'],
                'expired_at' => $data['expired_at'],
                'used_mac'   => $data['mac'],
                'used_ip'    => $data['ip'],
                'status'     => 'used',
            ]);

            if ($voucher->reseller_id) {
                ResellerCommission::create([
                    'reseller_id'     => $voucher->reseller_id,
                    'hotspot_user_id' => $voucher->id,
                    'type'            => 'earn',
                    'amount'          => $voucher->cost_price,
                ]);
            }
        });

        $this->deleteScript(
            $mikrotik,
            $script['.id']
        );

        return 1;
    }

    protected function parseEvent(string $event): array
    {
        [
            $type,
            $username,
            $usedAt,
            $expiredAt,
            $mac,
            $ip,
        ] = explode('|', $event);

        return [
            'username' => $username,
            'used_at' => Carbon::parse($usedAt),
            'expired_at' => Carbon::parse($expiredAt),
            'mac' => $mac,
            'ip' => $ip,
        ];
    }

    protected function deleteScript(
        MikrotikService $mikrotik,
        string $id
    ): void {

        $mikrotik->post('/system/script/remove', [
            '.id' => $id,
        ]);
    }
}