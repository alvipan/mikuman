<?php

use Livewire\Component;
use App\Models\HotspotProfile;
use App\Models\Router;
use App\Services\Hotspot\HotspotProfileService;

new class extends Component
{
    public $routers;

    public $packageId;

    public $router_id;
    public $name;
    public $sale_price = 0;
    public $cost_price = 0;
    public $validity;
    public $shared_users = 1;
    public $rate_limit;
    public $status = true;

    public $showModal = false;

    protected function rules()
    {
        return [
            'router_id' => 'required|exists:routers,id',
            'name' => 'required',
            'sale_price' => 'required|numeric',
            'cost_price' => 'nullable|numeric',
            'validity' => 'nullable',
            'shared_users' => 'required|integer|min:1',
            'rate_limit' => 'nullable',
        ];
    }

    public function mount()
    {
        $this->routers = Router::orderBy('name')->get();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $p = HotspotProfile::findOrFail($id);

        $this->packageId = $p->id;
        $this->router_id = $p->router_id;
        $this->name = $p->name;
        $this->sale_price = $p->sale_price;
        $this->cost_price = $p->cost_price;
        $this->validity = $p->validity;
        $this->shared_users = $p->shared_users;
        $this->rate_limit = $p->rate_limit;
        $this->status = $p->status;

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $router = Router::findOrFail($this->router_id);

        $service = new HotspotProfileService($router);

        $data = [
            'name' => $this->name,
            'sale_price' => $this->sale_price,
            'cost_price' => $this->cost_price,
            'validity' => $this->validity,
            'shared_users' => $this->shared_users,
            'rate_limit' => $this->rate_limit,
            'status' => $this->status,
        ];

        if ($this->packageId) {

            $profile = HotspotProfile::findOrFail($this->packageId);

            $service->update($profile, $data);

        } else {

            $service->create(array_merge($data, [
                'router_id' => $this->router_id
            ]));
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id)
    {
        $profile = HotspotProfile::findOrFail($id);
        $router = Router::findOrFail($profile->router_id);
        $service = new HotspotProfileService($router);

        $service->delete($profile);
    }

    private function resetForm()
    {
        $this->reset([
            'packageId',
            'router_id',
            'name',
            'sale_price',
            'cost_price',
            'validity',
            'shared_users',
            'rate_limit',
            'status'
        ]);

        $this->shared_users = 1;
        $this->status = true;
    }

    public function render()
    {
        return $this->view([
            'packages' => HotspotProfile::with('router')
                ->latest()
                ->get()
        ]);
    }
};