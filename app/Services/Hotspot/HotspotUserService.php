<?php

namespace App\Services\Hotspot;

use App\Models\Router;
use App\Services\Mikrotik\MikrotikService;
use App\Services\Voucher\VoucherService;

class HotspotUserService
{
    protected MikrotikService $mikrotik;

    public function __construct(Router $router)
    {
        $this->mikrotik = new MikrotikService($router);
    }

    public function create(array $data)
    {
        return $this->mikrotik->put('/ip/hotspot/user', $data);
    }

    public function update(string $id, array $data)
    {
        return $this->mikrotik->patch('/ip/hotspot/user/' . $id, [
            'name' => $data['name'],
            'password' => $data['password'],
        ]);
    }

    public function delete(string $id)
    {
        return $this->mikrotik->delete('/ip/hotspot/user/'.$id);
    }

    public function all()
    {
        return $this->mikrotik->get('/ip/hotspot/user');
    }

    public function active()
    {
        return $this->mikrotik->get('/ip/hotspot/active');
    }

    public function generateBatch(HotspotProfile $profile, int $qty, ?string $comment = null): array
    {
        $voucherService = new VoucherService();

        $codes = $voucherService->generateBatch($qty, $profile->code_length);

        $results = [];

        foreach ($codes as $code) {

            try {
                $res = $this->create([
                    'name' => $code,
                    'password' => $code,
                    'profile' => $profile->name,
                    'comment' => $this->buildComment($code, $comment),
                ]);

                $results[] = [
                    'username' => $code,
                    'password' => $code,
                    'status' => 'success',
                    'mikrotik_id' => $res['.id'] ?? null,
                ];

            } catch (\Throwable $e) {

                // retry sekali pakai kode baru
                $newCode = $voucherService->generateCode($profile->code_length);

                try {
                    $res = $this->create([
                        'name' => $newCode,
                        'password' => $newCode,
                        'profile' => $profile->name,
                        'comment' => $this->buildComment($newCode, $comment),
                    ]);

                    $results[] = [
                        'username' => $newCode,
                        'password' => $newCode,
                        'status' => 'success',
                        'mikrotik_id' => $res['.id'] ?? null,
                    ];

                } catch (\Throwable $e2) {
                    $results[] = [
                        'username' => $code,
                        'password' => $code,
                        'status' => 'failed',
                        'error' => $e2->getMessage(),
                    ];
                }
            }

            usleep(100000);
        }

        return $results;
    }
}