<?php

use Livewire\Component;
use App\Services\Pppoe\PppoeAggregatorService;

new class extends Component
{
    public array $results = [];

    public function mount(PppoeAggregatorService $service)
    {
        $this->load($service);
    }

    public function load(PppoeAggregatorService $service)
    {
        $this->results = $service->active();
    }

    public function refresh(PppoeAggregatorService $service)
    {
        $this->load($service);
    }

    public function render()
    {
        return $this->view();
    }
};