<?php

namespace App\Traits;

use Livewire\Attributes\On;
use App\Helpers\Mikrotik;

trait Processor {

    public $config;
    public $item = [];
    public $data = [];
    public $selected = [];

    public function getData() 
    {
        $response = Mikrotik::request($this->config['getDataUrl']);
        foreach ($response as $i => $v) {
            $response[$i]['id'] = $response[$i]['.id'];
            unset($response[$i]['.id']);
        }

        if (isset($this->config['route']) && $this->config['route'] === 'hotspot.profiles') {
            $this->data = $this->parseHotspotProfiles($response);
        } else {
            $this->data = $response;
        }
    }

    public function save()
    {
        $data = [];

        foreach($this->item as $key => $value)
        {
            if (!empty($value)) {
                if ($key === 'id') {
                    $data['.id'] = $value;
                } else {
                    $data[str_replace('_','-',$key)] = $value;
                }
            }
        }

        $response = Mikrotik::request(
            isset($data['.id']) ? $this->config['updateDataUrl'] : $this->config['addDataUrl'],
            $data
        );

        if (!empty($response['!trap'][0]['message'])) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => $response['!trap'][0]['message']
            ]);
            return;
        }

        $this->getData();
        $this->dispatch('update-data', data: $this->data);
    }

    #[On('remove')]
    public function remove($selected = []) 
    {
        foreach($selected as $item) {
            $response = Mikrotik::request($this->config['removeDataUrl'], [
                '.id' => $item['id']
            ]);

            if (!empty($response['!trap'])) {
                $this->dispatch('alert', [
                    'type' => 'error',
                    'message' => $response['!trap'][0]['message']
                ]);
                return;
            }
        }

        $this->getData();
        $this->dispatch('update-data', data: $this->data);
    }

    public function parseHotspotProfiles($data) 
    {
        $profiles = [];
        foreach ($data as $profile) {
            if (isset($profile['on-login'])) {
                preg_match('/\(\"(.*?)\"\)/', $profile['on-login'], $match);
                $data = explode(',', $match[1],);
            }
            
            $item = [
                'id'            => $profile['id'],
                'name'          => $profile['name'],
                'ip-pool'       => $profile['address-pool'] ?? 'none' ,
                'rate-limit'    => $profile['rate-limit'] ?? '',
                'shared-users'  => $profile['shared-users'],
                'parent-queue'  => $profile['parent-queue'],
                'expire-mode'   => !empty($data[1]) ? $data[1] : 'none',
                'price'         => !empty($data[2]) ? $data[2] : '0',
                'validity'      => !empty($data[3]) ? $data[3] : '',
                'sprice'        => !empty($data[4]) ? $data[4] : '0',
                'lock-users'    => !empty($data[6]) ? $data[6] : 'Disable',
                'lock-server'   => !empty($data[7]) ? $data[7] : 'Disable',
            ];
            $profiles[] = $item;
        }
        return $profiles;
    }
}