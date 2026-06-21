<?php

use Livewire\Component;
use App\Models\Customer;
use App\Models\Router;
use App\Models\PppoeProfile;
use App\Services\Customer\CustomerService;
use App\Services\Pppoe\PppoeSecretService;

new class extends Component
{
    // form
    public $name;
    public $phone;
    public $address;

    public $customerId = null;
    public $isEdit = false;
    public $showModal = false;

    // -----------------------------
    // Computed Properties
    // -----------------------------
    public function getCustomersProperty()
    {
        return Customer::orderBy('name')->get();
    }

    public function getRoutersProperty()
    {
        return Router::all();
    }

    public function getProfilesProperty()
    {
        return PppoeProfile::all();
    }

    // -----------------------------
    // Actions
    // -----------------------------
    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $customer = Customer::findOrFail($id);

        $this->customerId = $customer->id;
        $this->name = $customer->name;
        $this->phone = $customer->phone;
        $this->address = $customer->address;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        if ($this->isEdit) {
            Customer::findOrFail($this->customerId)->update([
                'name' => $this->name,
                'phone' => $this->phone,
                'address' => $this->address,
            ]);

            $this->dispatch('notify', type: 'success', message: 'Customer updated');
        } else {
            app(CustomerService::class)->create([
                'name' => $this->name,
                'phone' => $this->phone,
                'address' => $this->address,
            ]);

            $this->dispatch('notify', type: 'success', message: 'Customer created');
        }

        $this->showModal = false;
    }

    public function delete(int $id): void
    {
        $customer = Customer::findOrFail($id);

        foreach ($customer->secrets as $secret) {
            if ($secret->mikrotik_id) {
                $service = new PppoeSecretService($secret->router);
                $service->delete($secret->mikrotik_id); 
            }
        }

        $customer->delete();

        $this->dispatch('notify', type: 'success', message: 'Customer deleted');
    }

    protected function resetForm(): void
    {
        $this->reset([
            'customerId',
            'name',
            'phone',
            'address',
            'isEdit',
        ]);
    }

    public function render()
    {
        return $this->view();
    }
};