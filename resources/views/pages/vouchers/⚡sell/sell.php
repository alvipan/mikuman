<?php

use Livewire\Component;
use App\Models\Router;
use App\Models\Reseller;
use App\Models\HotspotProfile;
use App\Services\Sales\SaleService;

new class extends Component
{
    public $router_id;
    public $profile_id;
    public $quantity = 1;

    public $routers = [];
    public $profiles = [];

    public $vouchers = [];

    public $showResult = false;

    public function mount()
    {
        $user = auth()->user();

        // ADMIN
        if ($user->is_admin) {
            $this->routers = Router::all();
        }

        // RESELLER
        if ($user->is_reseller) {
            $this->router_id = $user->router_id;
            $this->loadProfiles();
        }
    }

    public function updatedRouterId()
    {
        $this->loadProfiles();
    }

    public function loadProfiles()
    {
        $this->profiles = HotspotProfile::where(
            'router_id',
            $this->router_id
        )->get();
    }

    public function generate(SaleService $saleService)
    {
        $router = Router::findOrFail($this->router_id);
        $profile = HotspotProfile::findOrFail($this->profile_id);

        $this->vouchers = $saleService->sell(
            $router,
            $profile,
            $this->quantity
        );

        $this->showResult = true;
    }

    public function render()
    {
        return $this->view()->title('Sell Voucher');
    }
};