<?php

use Livewire\Component;
use App\Models\Router;
use App\Models\Customer;
use App\Models\PppoeSecret;
use App\Models\PppoeProfile;
use App\Services\Pppoe\PppoeSecretService;
use App\Services\Payment\PaymentService;

new class extends Component
{
    public $routers;
    public $profiles = [];
    public Customer $customer;
    public ?int $editingSecretId = null;
    public bool $showSecretModal = false;

    public array $secretForm = [
        'router_id' => null,
        'profile_id' => null,
        'username' => '',
        'password' => '',
        'local_address' => null,
        'remote_address' => null,
    ];

    /*
    |--------------------------------------------------------------------------
    | Lifecycle
    |--------------------------------------------------------------------------
    */

    public function mount(Customer $customer): void
    {
        $this->customer = $customer;
        $this->loadCustomer($customer);

        $this->routers = Router::query()
            ->orderBy('name')
            ->get();
    }

    public function loadProfiles(): void
    {
        $routerId = $this->secretForm['router_id'];

        if (! $routerId) {

            $this->profiles = [];

            $this->secretForm['profile_id'] = null;

            return;
        }

        $this->profiles = PppoeProfile::query()
            ->where('router_id', $routerId)
            ->orderBy('name')
            ->get();

        $this->secretForm['profile_id'] = null;
    }

    /*
    |--------------------------------------------------------------------------
    | Secret CRUD
    |--------------------------------------------------------------------------
    */

    public function createSecret(): void
    {
        $this->resetSecretForm();

        $this->showSecretModal = true;
    }

    public function editSecret(int $secretId): void
    {
        $secret = $this->findSecret($secretId);

        $this->profiles = PppoeProfile::query()
            ->where('router_id', $secret->router_id)
            ->orderBy('name')
            ->get();

        $this->editingSecretId = $secret->id;

        $this->secretForm = [
            'router_id'  => $secret->router_id,
            'profile_id' => $secret->profile_id,
            'username'   => $secret->username,
            'password'   => $secret->password,
            'local_address' => $secret->local_address,
            'remote_address' => $secret->remote_address,
        ];

        $this->showSecretModal = true;
    }

    public function saveSecret(): void
    {
        $data = validator(
            $this->secretForm,
            [
                'router_id' => ['required', 'exists:routers,id'],
                'profile_id' => ['nullable', 'exists:pppoe_profiles,id'],
                'username' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'max:255'],
                'local_address' => ['nullable', 'ip'],
                'remote_address' => ['nullable', 'ip'],
            ]
        )->validate();

        try {

            if ($this->editingSecretId) {

                $secret = $this->findSecret(
                    $this->editingSecretId
                );
            
                $secret->update([
                    'profile_id' => $data['profile_id'],
                    'username'   => $data['username'],
                    'password'   => $data['password'],
                    'local_address' => $data['local_address'],
                    'remote_address' => $data['remote_address'],
                ]);
            
                $secret->load('profile', 'router');
            
                if ($secret->mikrotik_id) {
            
                    $this->pppoe($secret)->update(
                        $secret->mikrotik_id,
                        [
                            'name'     => $secret->username,
                            'password' => $secret->password,
                            'profile'  => $secret->profile?->name,
                            'local-address' => $secret->local_address ?? '',
                            'remote-address' => $secret->remote_address ?? '',
                        ]
                    );
                }
            
            } else {
            
                $secret = PppoeSecret::create([
                    'router_id'   => $data['router_id'],
                    'profile_id'  => $data['profile_id'],
                    'customer_id' => $this->customer->id,
                    'username'    => $data['username'],
                    'password'    => $data['password'],
                    'local_address' => $data['local_address'],
                    'remote_address' => $data['remote_address'],
                    'disabled'    => true,
                    'activated_at'=> now(),
                    'expired_at'  => now(),
                ]);
            
                $secret->load('profile', 'router');
            
                $response = $this->pppoe($secret)->create([
                    'name'     => $secret->username,
                    'password' => $secret->password,
                    'profile'  => $secret->profile?->name,
                    'local-address' => $secret->local_address ?? '',
                    'remote-address' => $secret->remote_address ?? '',
                    'service'  => 'pppoe',
                    'disabled' => 'yes',
                ]);
            
                $secret->update([
                    'mikrotik_id' => $response['.id'] ?? null,
                ]);
            }

            $this->reloadCustomer();

            $this->resetSecretForm();

            $this->showSecretModal = false;

            $this->dispatch(
                'notify',
                'Secret berhasil disimpan'
            );

        } catch (\Throwable $e) {

            report($e);

            $this->dispatch(
                'notify',
                $e->getMessage(),
                'error'
            );
        }
    }

    public function deleteSecret(int $secretId): void
    {
        $secret = $this->findSecret($secretId);

        try {

            if ($secret->mikrotik_id) {
                $this->pppoe($secret)
                    ->delete($secret->mikrotik_id);
            }

            $secret->delete();

            if ($this->editingSecretId === $secretId) {
                $this->editingSecretId = null;
            }

            $this->showSecretModal = false;

            $this->reloadCustomer();

            $this->dispatch(
                'notify',
                'Secret berhasil dihapus'
            );

        } catch (\Throwable $e) {

            report($e);

            $this->dispatch(
                'notify',
                $e->getMessage(),
                'error'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Secret State
    |--------------------------------------------------------------------------
    */

    public function activateSecret(int $secretId): void
    {
        $secret = $this->findSecret($secretId);

        try {

            if ($secret->mikrotik_id) {

                $this->pppoe($secret)
                    ->enable($secret->mikrotik_id);
            }

            $secret->update([
                'disabled' => false,
            ]);

            $this->reloadCustomer();

            $this->dispatch(
                'notify',
                'Secret berhasil diaktifkan'
            );

        } catch (\Throwable $e) {

            report($e);

            $this->dispatch(
                'notify',
                $e->getMessage(),
                'error'
            );
        }
    }

    public function suspendSecret(int $secretId): void
    {
        $secret = $this->findSecret($secretId);

        try {

            if ($secret->mikrotik_id) {

                $this->pppoe($secret)
                    ->disable($secret->mikrotik_id);
            }

            $secret->update([
                'disabled' => true,
            ]);

            $this->reloadCustomer();

            $this->dispatch(
                'notify',
                'Secret berhasil disuspend'
            );

        } catch (\Throwable $e) {

            report($e);

            $this->dispatch(
                'notify',
                $e->getMessage(),
                'error'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Payment
    |--------------------------------------------------------------------------
    */

    public function pay(
        int $secretId,
        PaymentService $payment
    ): void {

        $secret = $this->findSecret($secretId);

        try {

            $payment->pay(
                secret: $secret,
                months: 1,
            );

            $this->reloadCustomer();

            $this->dispatch(
                'notify',
                'Pembayaran berhasil'
            );

        } catch (\Throwable $e) {

            report($e);

            $this->dispatch(
                'notify',
                $e->getMessage(),
                'error'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    protected function resetSecretForm(): void
    {
        $this->editingSecretId = null;

        $this->profiles = [];

        $this->secretForm = [
            'router_id' => null,
            'profile_id' => null,
            'username' => '',
            'password' => '',
            'local_address' => null,
            'remote_address' => null,
        ];
    }

    protected function pppoe(
        PppoeSecret $secret
    ): PppoeSecretService {

        return new PppoeSecretService(
            $secret->router
        );
    }

    protected function findSecret(
        int $secretId
    ): PppoeSecret {

        return $this->customer
            ->secrets()
            ->with([
                'router',
                'profile',
            ])
            ->findOrFail($secretId);
    }

    protected function loadCustomer(
        Customer $customer
    ): void {

        $this->customer = $customer->load([
            'secrets.router',
            'secrets.profile',
        ]);
    }

    protected function reloadCustomer(): void
    {
        $this->loadCustomer(
            $this->customer->fresh()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return $this->view()
            ->title('Customer Detail');
    }
};