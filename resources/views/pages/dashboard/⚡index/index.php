<?php

use Livewire\Component;
use App\Services\Dashboard\DashboardService;

new class extends Component
{
    public string $period = 'today';

    public array $stats = [];
    public array $revenue = [];
    public array $transactions = [];

    public function mount(DashboardService $dashboard): void
    {
        $this->loadData($dashboard);
    }

    public function updatedPeriod(DashboardService $dashboard): void
    {
        $this->loadData($dashboard);
    }

    protected function loadData(
        DashboardService $dashboard
    ): void {
        $this->stats = $dashboard->stats();

        $this->revenue = $dashboard->revenue(
            $this->period
        );

        $this->transactions = $dashboard->transactions(
            $this->period
        );
    }

    public function render()
    {
        return $this->view()
            ->title('Dashboard');
    }
};