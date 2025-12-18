<?php

namespace App\Livewire\Hotspot;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Helpers\Mikrotik;
use App\Traits\Processor;

class HotspotUsers extends Component
{
    use Processor;
    public $profiles, $servers, $comments, $selected = [];

    // generate fields
    public $quantity = '1';
    public $server = 'all';
    public $credentialType = 'unp';
    public $credentialLength = '4';
    public $profile = 'default';
    public $comment = '';

    // edit fields
    public $id = '';
    public $name = '';
    public $password = '';

    public function mount()
    {
        $this->config = [
            'getDataUrl' => '/ip/hotspot/user/print',
            'addDataUrl' => '/ip/hotspot/user/add',
            'updateDataUrl' => '/ip/hotspot/user/set',
            'removeDataUrl' => '/ip/hotspot/user/remove'
        ];
        $this->getData();
        $this->getComments();
        $this->getProfiles();
        $this->getServers();
    }

    public function generate() {
        $time = time();
        for ($i = 0; $i < $this->quantity; $i++) {

            $name = $this->randString($this->credentialLength);
            $pass = $this->credentialType == 'unp' ? $this->randString($this->credentialLength) : $name;

            $user = [
                'name'      => $name,
                'password'  => $pass,
                'server'    => $this->server,
                'profile'   => $this->profile,
                'comment'   => !empty($this->comment) ? 'vc-'.$time.'-'.$this->comment : 'vc-'.$time
            ];

            $response = Mikrotik::request('/ip/hotspot/user/add', $user);
            if (!empty($response['!trap'][0]['message'])) {
                $this->dispatch('alert', [
                    'type' => 'error',
                    'message' => $response['!trap'][0]['message']
                ]);
                return;
            }
        }

        $this->getData();
        $this->getComments();
        $this->dispatch('update-data', data: $this->data);
    }
    
    private function getProfiles() 
    {
        $profiles = [];
        $response = Mikrotik::request('/ip/hotspot/user/profile/print');

        if ($response) {
            foreach ($response as $profile) {
                if (isset($profile['on-login'])) {
                    preg_match('/\(\"(.*?)\"\)/', $profile['on-login'], $match);
                    $data = explode(',', $match[1],);
                }

                $profiles[] = [
                    'id'    => $profile['.id'],
                    'name'  => $profile['name'],
                ];
            }
        }
        $this->profiles = $profiles;
    }

    public function getServers() {
        $servers = [];
        $response = Mikrotik::request('/ip/hotspot/print');
        if ($response) {
            foreach ($response as $item) {
                $item['id'] = $item['.id'];
                unset($item['.id']);
                $servers[] = $item;
            }
        }
        $this->servers = $servers;
    }

    public function getComments()
    {
        $comments = [];
        foreach ($this->data as $user) {
            if (
                isset($user['comment']) && 
                !in_array($user['comment'], $comments) &&
                str_contains($user['comment'], 'vc-')
            ) {
                $comments[] = $user['comment'];
            }
        }
        $this->comments = $comments;
    }

    public function formatBytes($bytes, $precision = 2)
    { 
        $units = ['Bytes', 'KiB', 'MiB', 'GiB', 'TiB']; 
    
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
    
        $bytes /= pow(1024, $pow);
        // this will also work in place of the above line:
        // $bytes /= (1 << (10 * $pow)); 
    
        return round($bytes, $precision) .' '. $units[$pow]; 
    }

    private function randString($length)
    {
        $char = '2346789ABCDEFGHJKLMNPQRTUVWXYZ';
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $char[rand(0, strlen($char) -1)];
        }
        return $result;
    }

    public function render()
    {
        return view('livewire.hotspot.hotspot-users');
    }
}
