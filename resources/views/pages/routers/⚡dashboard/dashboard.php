<?php

use Livewire\Component;
use App\Models\Router;
use App\Services\MikrotikService;

new class extends Component
{
    public Router $router;

    public array $stats = [];
    public array $traffic = [];
    public array $logs = [];
    public array $activeUsers = [];

    protected $listeners = [
        'refreshDashboard' => '$refresh'
    ];

    public function mount()
    {
        $this->router = Router::findOrFail(session('active_router_id'));
        $this->loadDashboard();
        $this->loadTraffic();
    }

    public function loadDashboard()
    {
        $service = new MikrotikService($this->router);
        $this->stats = $service->dashboard();
    }

    public function loadTraffic()
    {
        $service = new MikrotikService($this->router);

        $data = $service->traffic('HOTSPOT');

        $download = $data['rx'];
        $upload   = $data['tx'];

        $this->dispatch('traffic-updated', [
            'download' => $download,
            'upload' => $upload,
        ]);
    }

};