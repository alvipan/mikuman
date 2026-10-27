<?php

namespace App\Services\Mikrotik;

use App\Models\Router;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Pool;
use Throwable;

class MikrotikService
{
    protected Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    protected function client()
    {
        return Http::withBasicAuth($this->router->username, $this->router->password)
            ->baseUrl($this->router->base_url)
            ->withoutVerifying()
            ->timeout(10)
            ->acceptJson();
    }

    protected function request(string $method, string $endpoint, array $data = [])
    {
        try {
            $response = $this->client()->$method($endpoint, $data);
            if ($response->failed()) throw new RequestException($response);
            return $response->json();
        } catch (Throwable $e) {
            Log::error("MIKROTIK ERROR [{$this->router->name}] {$endpoint}: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    public function get(string $endpoint)
    {
        return $this->request('get', $endpoint);
    }
    public function post(string $endpoint, array $data = [])
    {
        return $this->request('post', $endpoint, $data);
    }
    public function patch(string $endpoint, array $data = [])
    {
        return $this->request('patch', $endpoint, $data);
    }
    public function put(string $endpoint, array $data = [])
    {
        return $this->request('put', $endpoint, $data);
    }
    public function delete(string $endpoint)
    {
        return $this->request('delete', $endpoint);
    }

    public function isError($result): bool
    {
        return is_array($result) && isset($result['error']);
    }

    public function listResource(string $endpoint): array
    {
        $result = $this->get($endpoint);
        return is_array($result) && !$this->isError($result) ? $result : [];
    }

    public function findByName(array $items, string $name): ?array
    {
        foreach ($items as $item) {
            if (($item['name'] ?? null) === $name) return $item;
        }
        return null;
    }

    public function ensureResource(string $endpoint, string $name, array $data = []): array
    {
        $items = $this->listResource($endpoint);
        $existing = $this->findByName($items, $name);

        if ($existing) {
            if (empty($data)) return ['success' => true, 'action' => 'exists'];

            $result = $this->post($endpoint . '/set', array_merge(['numbers' => $existing['.id']], $data));
            if ($this->isError($result)) throw new \RuntimeException("Gagal update {$name}: " . ($result['error'] ?? 'Unknown'));
            return ['success' => true, 'action' => 'updated'];
        }

        $result = $this->put($endpoint, array_merge(['name' => $name], $data));
        if ($this->isError($result)) throw new \RuntimeException("Gagal buat {$name}: " . ($result['error'] ?? 'Unknown'));
        return ['success' => true, 'action' => 'created'];
    }

    // --- LANGKAH-LANGKAH PROVISIONING ---

    public function stepCheckRouter()
    {
        $res = $this->get('/system/resource');
        if ($this->isError($res)) throw new \RuntimeException($res['error']);
        return true;
    }

    public function stepSetupBridge($bridgeName)
    {
        return $this->ensureResource('/interface/bridge', $bridgeName, ['comment' => 'mikuman-managed']);
    }

    public function stepSetupPool($hsSubnet, $pppoeSubnet, $lanIp)
    {
        $convertCidrToRange = function ($cidr) {
            $parts = explode('/', $cidr);
            $ip = $parts[0];
            $prefix = (int) ($parts[1] ?? 24);
            $octets = explode('.', $ip);
            if ($prefix == 16) return "{$octets[0]}.{$octets[1]}.0.1-{$octets[0]}.{$octets[1]}.255.254";
            return "{$octets[0]}.{$octets[1]}.{$octets[2]}.2-{$octets[0]}.{$octets[1]}.{$octets[2]}.254";
        };

        $this->ensureResource('/ip/pool', 'mikuman-pool-hs', ['ranges' => $convertCidrToRange($hsSubnet)]);
        $this->ensureResource('/ip/pool', 'mikuman-pool-pppoe', ['ranges' => $convertCidrToRange($pppoeSubnet)]);
        $this->ensureResource('/ip/pool', 'mikuman-pool-local', ['ranges' => trim(explode('/', $lanIp)[0])]);
        return true;
    }

    public function stepSetupHotspot($bridgeName)
    {
        $this->ensureResource('/ip/hotspot/profile', 'mikuman-hs-profile', [
            'hotspot-address' => '0.0.0.0',
            'dns-name' => 'login.wifi',
            'use-radius' => 'yes'
        ]);
        $this->ensureResource('/ip/hotspot', 'mikuman-hotspot', [
            'interface' => $bridgeName,
            'profile' => 'mikuman-hs-profile',
            'address-pool' => 'mikuman-pool-hs',
            'disabled' => 'no'
        ]);
        return true;
    }

    public function stepSetupPppoe($bridgeName)
    {
        $this->ensureResource('/ppp/profile', 'mikuman-ppp-profile', [
            'local-address' => 'mikuman-pool-local',
            'remote-address' => 'mikuman-pool-pppoe'
        ]);

        $servers = $this->listResource('/interface/pppoe-server/server');
        $existing = null;
        foreach ($servers as $srv) {
            if (($srv['service-name'] ?? '') === 'mikuman-pppoe') {
                $existing = $srv;
                break;
            }
        }

        if ($existing) {
            $res = $this->post('/interface/pppoe-server/server/set', ['numbers' => $existing['.id'], 'interface' => $bridgeName, 'default-profile' => 'mikuman-ppp-profile', 'disabled' => 'no']);
            if ($this->isError($res)) throw new \RuntimeException("Gagal update PPPoE: " . $res['error']);
        } else {
            $res = $this->put('/interface/pppoe-server/server', ['service-name' => 'mikuman-pppoe', 'interface' => $bridgeName, 'default-profile' => 'mikuman-ppp-profile', 'disabled' => 'no']);
            if ($this->isError($res)) throw new \RuntimeException("Gagal buat PPPoE: " . $res['error']);
        }
        return true;
    }

    public function stepSetupRadius($radiusIp, $radiusSecret)
    {
        $radiusConfigs = $this->listResource('/radius');
        foreach ($radiusConfigs as $rc) {
            if (isset($rc['comment']) && $rc['comment'] === 'mikuman-radius') $this->delete('/radius/' . rawurlencode($rc['.id']));
        }
        $res = $this->put('/radius', ['service' => 'hotspot,ppp', 'address' => $radiusIp, 'secret' => $radiusSecret, 'comment' => 'mikuman-radius']);
        if ($this->isError($res)) throw new \RuntimeException("Gagal setup RADIUS: " . $res['error']);
        $this->patch('/ppp/aaa', ['use-radius' => 'yes']);
        return true;
    }

    public function stepSetupWlan($ssid)
    {
        if (!$ssid) return 'Skip';
        $wireless = $this->listResource('/interface/wireless');
        foreach ($wireless as $wlan) {
            $this->post('/interface/wireless/set', ['numbers' => $wlan['.id'], 'ssid' => $ssid, 'mode' => 'ap-bridge', 'disabled' => 'no']);
        }
        return true;
    }

    public static function getStatuses($routers): array
    {
        $statuses = [];
        foreach ($routers as $router) $statuses[$router->id] = 'offline';
        $active = $routers->where('is_active', true)->values();
        if ($active->isEmpty()) return $statuses;

        try {
            $responses = Http::pool(function (Pool $pool) use ($active) {
                foreach ($active as $r) {
                    $pool->as((string) $r->id)->withBasicAuth($r->username, $r->password)
                        ->withoutVerifying()->timeout(4)->get(rtrim($r->base_url, '/') . '/system/resource');
                }
            });
            foreach ($active as $r) {
                if (isset($responses[(string) $r->id]) && $responses[(string) $r->id]->successful()) {
                    $statuses[$r->id] = 'online';
                }
            }
        } catch (Throwable $e) {
        }
        return $statuses;
    }
}
