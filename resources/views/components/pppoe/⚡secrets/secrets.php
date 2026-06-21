<?php

use Livewire\Component;
use App\Models\PppoeSecret;
use App\Models\Router;
use App\Services\Pppoe\PppoeSecretService;

new class extends Component
{
    public ?int $router_id = null;

    // -----------------------------
    // Computed Properties
    // -----------------------------
    public function getRoutersProperty()
    {
        return Router::all();
    }

    public function getSecretsProperty()
    {
        return PppoeSecret::with(['profile', 'customer'])
            ->when($this->router_id, fn ($q) => $q->where('router_id', $this->router_id))
            ->latest()
            ->get();
    }

    // -----------------------------
    // Actions
    // -----------------------------
    public function updatedRouterId(): void
    {
        // Tidak perlu loadData(), computed property otomatis refresh
    }

    public function toggle(int $id): void
    {
        $secret = PppoeSecret::findOrFail($id);
        $router = Router::findOrFail($secret->router_id);

        $service = app(PppoeSecretService::class, ['router' => $router]);

        if ($secret->disabled) {
            $service->enable($secret);
        } else {
            $service->disable($secret);
        }
    }

    public function delete(int $id): void
    {
        $secret = PppoeSecret::findOrFail($id);
        $router = Router::findOrFail($secret->router_id);

        app(PppoeSecretService::class, ['router' => $router])->delete($secret);

        $this->dispatch('notify', type: 'success', message: 'Secret deleted');
    }

    public function mount(): void
    {
        $this->router_id = $this->routers[0]['id'] ?? null;
    }

    public function render()
    {
        return $this->view();
    }
};