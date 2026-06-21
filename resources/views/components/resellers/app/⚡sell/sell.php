<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Router;
use App\Models\HotspotProfile;
use App\Services\Sales\SaleService;

new class extends Component
{
    public $balance = 0;
    public ?int $router_id = null;
    public ?int $profile_id = null;
    public int $quantity = 1;

    public $routers = [];
    public $profiles = [];

    public array $vouchers = [];

    public bool $checkout = false;

    public function mount()
    {
        $this->loadData();
    }

    #[On('sale-created')]
    public function loadData() {
        $user = auth()->user();

        $this->balance = $user->balance;

        if ($user->is_admin) {
            $this->routers = Router::query()->get();
        }

        if ($user->is_reseller) {
            $this->router_id = $user->router_id;
            $this->loadProfiles();
        }
    }

    public function updatedRouterId(): void
    {
        $this->reset('profile_id');
        $this->loadProfiles();
    }

    public function getSelectedProfileProperty()
    {
        return collect($this->profiles)->firstWhere('id', $this->profile_id);
    }

    public function getTotalProperty()
    {
        if (!$this->selectedProfile || !$this->quantity) {
            return 0;
        }

        return $this->selectedProfile->sale_price * $this->quantity;
    }

    protected function loadProfiles(): void
    {
        if (!$this->router_id) {
            $this->profiles = [];
            return;
        }

        $this->profiles = HotspotProfile::query()
            ->where('router_id', $this->router_id)
            ->get();
    }

    public function generate(SaleService $saleService): void
    {
        $this->validate([
            'router_id' => ['required', 'exists:routers,id'],
            'profile_id' => ['required', 'exists:hotspot_profiles,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $this->reset('vouchers');

        $router = Router::findOrFail($this->router_id);
        $profile = HotspotProfile::findOrFail($this->profile_id);

        $this->vouchers = $saleService->sell($router, $profile, $this->quantity);

        $this->dispatch('sale-created');

        \Flux::modal('result-modal')->show();
    }

    public function render()
    {
        return $this->view();
    }
};