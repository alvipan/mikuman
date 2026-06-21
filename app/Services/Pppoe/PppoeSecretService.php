<?php

namespace App\Services\Pppoe;

use App\Models\Router;
use App\Services\Mikrotik\MikrotikService;

class PppoeSecretService
{
    protected MikrotikService $mikrotik;

    public function __construct(Router $router)
    {
        $this->mikrotik = new MikrotikService($router);
    }

    /*
    |--------------------------------------------------------------------------
    | PPPoE Secret
    |--------------------------------------------------------------------------
    */

    public function all(): array
    {
        return $this->mikrotik->get('/ppp/secret');
    }

    public function active(): array
    {
        return $this->mikrotik->get('/ppp/active');
    }

    public function find(string $id): ?array
    {
        return collect($this->all())
            ->firstWhere('.id', $id);
    }

    public function findByUsername(string $username): ?array
    {
        return collect($this->all())
            ->firstWhere('name', $username);
    }

    public function create(array $data): array
    {
        return $this->mikrotik->put(
            '/ppp/secret',
            $data
        );
    }

    public function update(
        string $id,
        array $data
    ): array {
        return $this->mikrotik->patch(
            '/ppp/secret/' . $id,
            $data
        );
    }

    public function delete(string $id)
    {
        return $this->mikrotik->delete(
            '/ppp/secret/' . $id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function enable(string $id): array
    {
        return $this->update($id, [
            'disabled' => 'no',
        ]);
    }

    public function disable(string $id): array
    {
        return $this->update($id, [
            'disabled' => 'yes',
        ]);
    }

    public function updateProfile(
        string $id,
        string $profile
    ): array {
        return $this->update($id, [
            'profile' => $profile,
        ]);
    }

    public function setComment(
        string $id,
        string $comment
    ): array {
        return $this->update($id, [
            'comment' => $comment,
        ]);
    }
}