<?php

use Livewire\Component;
use App\Models\Router;
use App\Models\PppoeProfile;
use App\Services\Pppoe\PppoeProfileService;

new class extends Component
{
    public bool $showModal = false;
    public bool $isEdit = false;

    public ?PppoeProfile $editing = null;

    public array $form = [
        'router_id' => '',
        'name' => '',
        'rate_limit' => '',
        'price' => 0,
    ];

    // -----------------------------
    // Computed Properties
    // -----------------------------
    public function getRoutersProperty()
    {
        return Router::all();
    }

    public function getProfilesProperty()
    {
        return PppoeProfile::with('router')
            ->latest()
            ->get();
    }

    // -----------------------------
    // Helpers
    // -----------------------------
    public function service(int $routerId): PppoeProfileService
    {
        $router = Router::findOrFail($routerId);

        return new PppoeProfileService($router);
    }

    // -----------------------------
    // Actions
    // -----------------------------
    public function create(): void
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $this->editing = PppoeProfile::findOrFail($id);

        $this->form = [
            'router_id' => $this->editing->router_id,
            'name' => $this->editing->name,
            'rate_limit' => $this->editing->rate_limit,
            'price' => $this->editing->price,
        ];

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'form.router_id' => 'required|exists:routers,id',
            'form.name' => 'required|string',
            'form.rate_limit' => 'required|string',
            'form.price' => 'required|numeric|min:0',
        ]);

        $service = $this->service((int) $this->form['router_id']);

        try {
            if ($this->isEdit && $this->editing) {
                $service->update($this->editing, $this->form);
                $this->dispatch('notify', type: 'success', message: 'Profile updated');
            } else {
                $service->create($this->form);
                $this->dispatch('notify', type: 'success', message: 'Profile created');
            }
        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'error', message: $e->getMessage());
            return;
        }

        $this->showModal = false;
    }

    public function delete(int $id): void
    {
        $profile = PppoeProfile::findOrFail($id);
        $service = $this->service($profile->router_id);
        $service->delete($profile);

        $this->dispatch('notify', type: 'success', message: 'Profile deleted');
    }

    // -----------------------------
    // State
    // -----------------------------
    public function resetForm(): void
    {
        $this->form = [
            'router_id' => '',
            'name' => '',
            'rate_limit' => '',
            'price' => 0,
        ];

        $this->editing = null;
    }

    public function render()
    {
        return $this->view();
    }
};