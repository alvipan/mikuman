<?php

namespace App\Services\Payment;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use App\Models\Payment;
use App\Models\PppoeSecret;
use Illuminate\Support\Facades\DB;
use App\Services\Pppoe\PppoeSecretService;

class PaymentService
{
    public function pay(
        PppoeSecret $secret,
        int $months = 1,
        ?string $note = null,
    ): Payment {

        if ($months < 1) {
            throw new \InvalidArgumentException(
                'Jumlah bulan minimal 1.'
            );
        }

        $result = DB::transaction(function () use (
            $secret,
            $months,
            $note,
        ) {

            $secret = PppoeSecret::query()
                ->with(['profile', 'router'])
                ->lockForUpdate()
                ->findOrFail($secret->id);

            if (! $secret->customer_id) {
                throw new \RuntimeException(
                    'Secret tidak memiliki customer.'
                );
            }

            $amount = $this->calculateAmount(
                $secret,
                $months
            );

            $expiredAt = $this->calculateExpiredAt(
                $secret,
                $months
            );

            $payment = Payment::create([
                'pppoe_secret_id' => $secret->id,
                'amount'          => $amount,
                'months'          => $months,
                'note'            => $note,
                'paid_at'         => now(),
            ]);

            $secret->update([
                'expired_at' => $expiredAt,
                'disabled'   => false,
            ]);

            return [
                'payment'    => $payment,
                'secret'     => $secret->fresh(),
                'expired_at' => $expiredAt,
            ];
        });

        $this->syncMikrotik(
            $result['secret'],
            $result['expired_at']
        );

        return $result['payment']->fresh();
    }

    protected function calculateAmount(
        PppoeSecret $secret,
        int $months
    ): float {

        return (
            $secret->profile?->price ?? 0
        ) * $months;
    }

    protected function calculateExpiredAt(
        PppoeSecret $secret,
        int $months
    ): CarbonInterface {

        $baseDate = $secret->expired_at
            && $secret->expired_at->isFuture()
                ? $secret->expired_at->copy()
                : now();

        return $baseDate->addMonths($months);
    }

    protected function syncMikrotik(
        PppoeSecret $secret,
        CarbonInterface $expiredAt
    ): void {

        $pppoe = new PppoeSecretService(
            $secret->router
        );

        $pppoe->setComment(
            $secret->mikrotik_id,
            $this->buildComment($expiredAt)
        );

        $pppoe->enable(
            $secret->mikrotik_id
        );
    }

    protected function buildComment(
        CarbonInterface $expiredAt
    ): string {

        return sprintf(
            'EXP|%s',
            $expiredAt->format('Y-m-d')
        );
    }
}