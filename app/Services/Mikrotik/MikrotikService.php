<?php

namespace App\Services\Mikrotik;

use App\Models\Router;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class MikrotikService
{
    protected Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    protected function client()
    {
        return Http::withBasicAuth(
                $this->router->username,
                $this->router->password
            )
            ->baseUrl("http://{$this->router->host}/rest")
            ->timeout(5)
            ->acceptJson();
    }

    protected function request(string $method, string $endpoint, array $data = [])
    {
        try {

            $response = $this->client()->$method($endpoint, $data);

            if ($response->failed()) {
                throw new RequestException($response);
            }

            return $response->json();

        } catch (\Throwable $e) {

            logger()->error('MIKROTIK ERROR', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function get(string $endpoint)
    {
        return $this->request('get', $endpoint);
    }

    public function put(string $endpoint, array $data = [])
    {
        return $this->request('put', $endpoint, $data);
    }

    public function patch(string $endpoint, array $data = [])
    {
        return $this->request('patch', $endpoint, $data);
    }

    public function post(string $endpoint, array $data = [])
    {
        return $this->request('post', $endpoint, $data);
    }
    
    public function delete(string $endpoint)
    {
        return $this->request('delete', $endpoint);
    }

    protected function minifyScript(string $script): string
    {
        $script = preg_replace('/\s+/', ' ', $script);
        return trim($script);
    }

    public function ensureHotspotExpireScheduler()
    {
        $schedulers = $this->get('/system/scheduler');

        $exists = collect($schedulers)->contains('name', 'MIKUMAN-HOTSPOT-EXPIRE');

        if ($exists) {
            return;
        }

        $script = '
            :local now ([/system clock get date]." ".[/system clock get time]);

            :foreach i in=[/ip hotspot user find where comment~"EXP"] do={

                :local c [/ip hotspot user get $i comment];
                :local e [:pick $c 4 [:len $c]];

                :if ($now>$e) do={
                    /ip hotspot user remove $i;
                };
            };
        ';

        $this->post('/system/scheduler/add', [
            'name' => 'MIKUMAN-HOTSPOT-EXPIRE',
            'interval' => '5m',
            'on-event' => $this->minifyScript($script)
        ]);
    }

    public function ensurePppoeExpireScheduler()
    {
        $schedulers = $this->get('/system/scheduler');

        $exists = collect($schedulers)->contains('name', 'MIKUMAN-PPPOE-EXPIRE');

        if ($exists) {
            return;
        }

        $script = '
            :local now ([/system clock get date]." ".[/system clock get time]);

            :foreach i in=[/ppp secret find where comment~"EXP"] do={

                :local c [/ppp secret get $i comment];
                :local e [:pick $c 4 [:len $c]];
                :local user [/ppp secret get $i name];

                :if ($now>$e) do={

                    :if ([/ppp secret get $i disabled] = false) do={

                        :log warning ("MIKUMAN PPPoE EXPIRED: " . $user);

                        /ppp active remove [find name=$user];
                        /ppp secret set $i disabled=yes;
                    };
                };
            };
        ';

        $this->post('/system/scheduler/add', [
            'name' => 'MIKUMAN-PPPOE-EXPIRE',
            'interval' => '5m',
            'on-event' => $this->minifyScript($script),
        ]);
    }

    public function ensureEnvironment()
    {
        $this->ensureHotspotExpireScheduler();
        $this->ensurePppoeExpireScheduler();
    }
}