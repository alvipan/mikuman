<?php

use Livewire\Component;
use Carbon\Carbon;
use App\Services\Reports\RevenueReportService;

new class extends Component
{
    public string $period = 'month';
    public string $dateFrom;
    public string $dateTo;

    public array $summary = [];
    public array $trend = [];
    public array $transactions = [];

    public function mount(
        RevenueReportService $report,
    ): void {

        $this->dateFrom = now()
            ->startOfMonth()
            ->toDateString();

        $this->dateTo = now()
            ->endOfMonth()
            ->toDateString();

        $this->loadData($report);
    }

    public function updatedPeriod(
        RevenueReportService $report,
    ): void {

        match ($this->period) {

            'today' => [
                $this->dateFrom = now()->toDateString(),
                $this->dateTo = now()->toDateString(),
            ],

            'week' => [
                $this->dateFrom = now()->startOfWeek()->toDateString(),
                $this->dateTo = now()->endOfWeek()->toDateString(),
            ],

            'month' => [
                $this->dateFrom = now()->startOfMonth()->toDateString(),
                $this->dateTo = now()->endOfMonth()->toDateString(),
            ],

            'last_month' => [
                $this->dateFrom = now()->subMonth()->startOfMonth()->toDateString(),
                $this->dateTo = now()->subMonth()->endOfMonth()->toDateString(),
            ],

            'year' => [
                $this->dateFrom = now()->startOfYear()->toDateString(),
                $this->dateTo = now()->endOfYear()->toDateString(),
            ],

            default => null,
        };

        if ($this->period !== 'custom') {
            $this->loadData($report);
        }
    }

    public function updatedDateFrom(): void
    {
        $this->loadData(app(RevenueReportService::class));
    }

    public function updatedDateTo(): void
    {
        $this->loadData(app(RevenueReportService::class));
    }

    public function loadData(
        RevenueReportService $report,
    ): void {

        $from = Carbon::parse($this->dateFrom);
        $to = Carbon::parse($this->dateTo);

        $this->summary = $report
            ->summary($from, $to);

        $this->trend = $report
            ->revenueTrend($from, $to)
            ->toArray();

        $this->transactions = $report
            ->recentTransactions(
                $from,
                $to,
            )
            ->toArray();

        $this->dispatch(
            'trend-updated',
            trend: $this->trend,
        );
    }

    public function render()
    {
        return $this->view()->title('Reports');
    }
};