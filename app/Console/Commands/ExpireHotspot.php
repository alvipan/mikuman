<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HotspotUser;

class ExpireHotspot extends Command
{
    protected $signature = 'hotspot:expire';

    protected $description = 'Mark expired hotspot vouchers';

    public function handle(): int
    {
        $count = HotspotUser::query()
            ->where('status', 'used')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', now())
            ->update([
                'status' => 'expired',
            ]);

        $this->info("{$count} voucher(s) expired.");

        return self::SUCCESS;
    }
}