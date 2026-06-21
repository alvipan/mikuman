<?php

namespace App\Services\Pppoe;

use App\Models\Router;
use App\Models\PppoeProfile;
use App\Services\Mikrotik\MikrotikService;

class PppoeProfileService
{
    protected MikrotikService $mikrotik;
    protected Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->mikrotik = new MikrotikService($router);
    }

    /*
    |--------------------------------------------------------------------------
    | Mikrotik Source
    |--------------------------------------------------------------------------
    */

    public function all(): array
    {
        return $this->mikrotik->get('/ppp/profile');
    }

    public function findByName(string $name): ?array
    {
        return collect($this->all())->firstWhere('name', $name);
    }

    /*
    |--------------------------------------------------------------------------
    | Database (Scoped by Router)
    |--------------------------------------------------------------------------
    */

    public function list()
    {
        return PppoeProfile::query()
            ->where('router_id', $this->router->id)
            ->latest()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    public function create(array $data): PppoeProfile
    {
        $response = $this->mikrotik->put('/ppp/profile', [
            'name'        => $data['name'],
            'rate-limit'  => $data['rate_limit'] ?? null,
        ]);

        $mikrotikId = $response['.id'] ?? null;

        return PppoeProfile::create([
            'router_id'      => $this->router->id,
            'mikrotik_id'   => $mikrotikId,
            'name'          => $data['name'],
            'rate_limit'    => $data['rate_limit'] ?? null,
            'price'         => $data['price'] ?? 0,
        ]);
    }

    public function update(PppoeProfile $profile, array $data): void
    {
        $this->ensureSameRouter($profile);

        $this->mikrotik->patch('/ppp/profile/' . $profile->mikrotik_id, [
            'name'        => $data['name'],
            'rate-limit'  => $data['rate_limit'] ?? null,
        ]);

        $profile->update([
            'name'       => $data['name'],
            'rate_limit' => $data['rate_limit'] ?? null,
            'price'      => $data['price'] ?? $profile->price,
        ]);
    }

    public function delete(PppoeProfile $profile): void
    {
        $this->ensureSameRouter($profile);

        if ($profile->mikrotik_id) {
            $this->mikrotik->delete('/ppp/profile/' . $profile->mikrotik_id);
        }

        $profile->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Internal Helpers
    |--------------------------------------------------------------------------
    */

    protected function ensureSameRouter(PppoeProfile $profile): void
    {
        if ($profile->router_id !== $this->router->id) {
            throw new \Exception('Profile does not belong to this router');
        }
    }
}