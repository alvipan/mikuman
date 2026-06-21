<?php

namespace App\Services\Dashboard;

use Carbon\CarbonInterface;
use App\Models\HotspotUser;
use App\Models\PppoeSecret;
use App\Services\Router\RouterService;
use App\Services\Reports\RevenueReportService;

class DashboardService
{
    public function __construct(
        protected RevenueReportService $report,
        protected RouterService $routerService,
    ) {}

    public function stats(): array
    {
        return [
            'router' => $this->routerService->stats(),

            'voucher' => [
                'unused' => $this->report->unusedVoucherCount(),

                'used' => HotspotUser::query()
                    ->where('status', 'used')
                    ->count(),

                'expired' => HotspotUser::query()
                    ->where('status', 'expired')
                    ->count(),
            ],

            'pppoe' => [
                'active' => PppoeSecret::query()
                    ->where('disabled', false)
                    ->count(),

                'disabled' => PppoeSecret::query()
                    ->where('disabled', true)
                    ->count(),

                'expired' => PppoeSecret::query()
                    ->whereNotNull('expired_at')
                    ->where('expired_at', '<', now())
                    ->count(),
            ],
        ];
    }

    public function revenue(string $period): array
    {
        [$from, $to] = $this->resolveRange($period);

        return [
            'hotspot' => $this->report->hotspotRevenue($from, $to),

            'pppoe' => $this->report->pppoeRevenue($from, $to),

            'total' => $this->report->totalRevenue($from, $to),
        ];
    }

    public function transactions(
        string $period,
        int $limit = 10,
    ): array {
        [$from, $to] = $this->resolveRange($period);

        return $this->report
            ->recentTransactions($from, $to, $limit)
            ->values()
            ->toArray();
    }

    protected function resolveRange(string $period): array
    {
        return match ($period) {

            'today' => [
                now()->startOfDay(),
                now()->endOfDay(),
            ],

            'yesterday' => [
                now()->subDay()->startOfDay(),
                now()->subDay()->endOfDay(),
            ],

            'week' => [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ],

            'last_week' => [
                now()->subWeek()->startOfWeek(),
                now()->subWeek()->endOfWeek(),
            ],

            'month' => [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ],

            'last_month' => [
                now()->subMonth()->startOfMonth(),
                now()->subMonth()->endOfMonth(),
            ],

            default => [
                now()->startOfDay(),
                now()->endOfDay(),
            ],
        };
    }
}