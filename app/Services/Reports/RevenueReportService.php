<?php

namespace App\Services\Reports;

use App\Models\Payment;
use App\Models\HotspotUser;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RevenueReportService
{
    public function hotspotRevenue(
        CarbonInterface $from,
        CarbonInterface $to,
    ): float
    {
        return (float) HotspotUser::query()
            ->whereNotNull('used_at')
            ->whereBetween('used_at', [$from, $to])
            ->sum('sale_price');
    }

    public function pppoeRevenue(
        CarbonInterface $from,
        CarbonInterface $to,
    ): float
    {
        return (float) Payment::query()
            ->whereBetween('paid_at', [$from, $to])
            ->sum('amount');
    }

    public function totalRevenue(
        CarbonInterface $from,
        CarbonInterface $to,
    ): float
    {
        return $this->hotspotRevenue($from, $to)
            + $this->pppoeRevenue($from, $to);
    }

    public function unusedVoucherCount(): int
    {
        return HotspotUser::query()
            ->where('status', 'unused')
            ->count();
    }

    public function usedVoucherCount(
        CarbonInterface $from,
        CarbonInterface $to,
    ): int
    {
        return HotspotUser::query()
            ->where('status', 'used')
            ->whereBetween('used_at', [$from, $to])
            ->count();
    }

    public function expiredVoucherCount(
        CarbonInterface $from,
        CarbonInterface $to,
    ): int
    {
        return HotspotUser::query()
            ->where('status', 'expired')
            ->whereBetween('used_at', [$from, $to])
            ->count();
    }

    public function summary(
        CarbonInterface $from,
        CarbonInterface $to,
    ): array
    {
        $hotspotRevenue = $this->hotspotRevenue($from, $to);
        $pppoeRevenue = $this->pppoeRevenue($from, $to);

        return [
            'hotspot_revenue' => $hotspotRevenue,
            'pppoe_revenue' => $pppoeRevenue,
            'total_revenue' => $hotspotRevenue + $pppoeRevenue,

            'unused_vouchers' => $this->unusedVoucherCount(),
            'used_vouchers' => $this->usedVoucherCount($from, $to),
            'expired_vouchers' => $this->expiredVoucherCount($from, $to),
        ];
    }

    public function revenueTrend(
        CarbonInterface $from,
        CarbonInterface $to,
    ): Collection {
        $hotspot = HotspotUser::query()
            ->whereNotNull('used_at')
            ->whereBetween('used_at', [$from, $to])
            ->selectRaw('DATE(used_at) as date')
            ->selectRaw('SUM(sale_price) as revenue')
            ->groupBy(DB::raw('DATE(used_at)'))
            ->pluck('revenue', 'date');

        $pppoe = Payment::query()
            ->whereBetween('paid_at', [$from, $to])
            ->selectRaw('DATE(paid_at) as date')
            ->selectRaw('SUM(amount) as revenue')
            ->groupBy(DB::raw('DATE(paid_at)'))
            ->pluck('revenue', 'date');

        return collect(
            CarbonPeriod::create($from->toDateString(), $to->toDateString())
        )->map(function (CarbonInterface $date) use ($hotspot, $pppoe) {

            $key = $date->toDateString();

            $hotspotRevenue = (float) ($hotspot[$key] ?? 0);
            $pppoeRevenue = (float) ($pppoe[$key] ?? 0);

            return [
                'date' => $key,
                'hotspot' => $hotspotRevenue,
                'pppoe' => $pppoeRevenue,
                'total' => $hotspotRevenue + $pppoeRevenue,
            ];
        });
    }

    public function recentTransactions(
        CarbonInterface $from,
        CarbonInterface $to,
        int $limit = 10,
    ): Collection {

        $hotspot = HotspotUser::query()
            ->whereNotNull('used_at')
            ->whereBetween('used_at', [$from, $to])
            ->latest('used_at')
            ->limit($limit)
            ->get()
            ->map(fn (HotspotUser $user) => [
                'type' => 'hotspot',
                'date' => $user->used_at,
                'name' => $user->username,
                'amount' => (float) $user->sale_price,
            ]);

        $pppoe = Payment::query()
            ->whereBetween('paid_at', [$from, $to])
            ->with('pppoeSecret')
            ->latest('paid_at')
            ->limit($limit)
            ->get()
            ->map(fn (Payment $payment) => [
                'type' => 'pppoe',
                'date' => $payment->paid_at,
                'name' => $payment->pppoeSecret?->username,
                'amount' => (float) $payment->amount,
            ]);

        return $hotspot
            ->concat($pppoe)
            ->sortByDesc('date')
            ->take($limit)
            ->values();
    }
}