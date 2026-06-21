<?php

use Livewire\Component;
use App\Services\Hotspot\HotspotAggregatorService;

new class extends Component
{
    public function getActiveUsersProperty()
    {
        return app(HotspotAggregatorService::class)->active();
    }

    public function render()
    {
        return $this->view();
    }
};