<?php

namespace App\Services\Hotspot;

use App\Models\Router;
use App\Models\HotspotProfile;
use App\Services\Mikrotik\MikrotikService;

class HotspotProfileService
{
    protected MikrotikService $mikrotik;
    protected Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->mikrotik = new MikrotikService($router);
    }

    public function all(): array
    {
        return $this->mikrotik->get('/ip/hotspot/user/profile');
    }

    public function find(string $name): ?array
    {
        $profiles = $this->all();

        return collect($profiles)->firstWhere('name', $name);
    }

    public function create(array $data): HotspotProfile
    {
        $response = $this->mikrotik->put('/ip/hotspot/user/profile', [
            'name' => $data['name'],
            'rate-limit' => $data['rate_limit'],
            'shared-users' => $data['shared_users'],
            'on-login' => $this->generateScript($data),
        ]);

        $mikrotikId = $response['.id'] ?? null;

        return HotspotProfile::create([
            'router_id' => $this->router->id,
            'mikrotik_id' => $mikrotikId,
            'name' => $data['name'],
            'sale_price' => $data['sale_price'],
            'cost_price' => $data['cost_price'],
            'validity' => $data['validity'],
            'shared_users' => $data['shared_users'],
            'rate_limit' => $data['rate_limit'],
            'status' => $data['status'],
        ]);
    }

    public function update(HotspotProfile $profile, array $data)
    {
        $this->mikrotik->patch('/ip/hotspot/user/profile/' . $profile->mikrotik_id, [
            'name' => $data['name'],
            'rate-limit' => $data['rate_limit'],
            'shared-users' => $data['shared_users'],
            'on-login' => $this->generateScript($data),
        ]);

        $profile->update($data);
    }

    public function delete(HotspotProfile $profile)
    {
        $this->mikrotik->delete('/ip/hotspot/user/profile/' . $profile->mikrotik_id);

        $profile->delete();
    }

    protected function generateScript(array $data): string
    {
        return $this->minifyScript('
            :local u $user;
            :local mac $"mac-address";
            :local ip $address;
            :local date [/system clock get date];
            :local time [:pick [/system clock get time] 0 5];

            :local c [/ip hotspot user get $u comment];

            :if ([:find $c "EXP|"] != 0) do={

                /system scheduler add name=("tmp-exp-".$u) interval=' . $data['validity'] . ' start-date=$date start-time=$time;

                :delay 2s;

                :local exp [/system scheduler get [find name=("tmp-exp-".$u)] next-run];

                /ip hotspot user set $u comment=("EXP|".$exp);

                /system scheduler remove [find name=("tmp-exp-".$u)];

                :local log ("VCR|".$u."|".$date." ".$time."|".$exp."|".$mac."|".$ip);

                /system script add name=("LOG-".
                
                $u."-".$date."-".$time) source="" comment=$log;
            };
        ');
    }

    protected function minifyScript(string $script): string
    {
        $script = preg_replace('/\s+/', ' ', $script);
        return trim($script);
    }
}