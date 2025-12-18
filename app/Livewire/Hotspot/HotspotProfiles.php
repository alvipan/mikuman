<?php

namespace App\Livewire\Hotspot;

use Illuminate\Support\Facades\file;
use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\Router;
use App\Helpers\Mikrotik;
use App\Traits\Processor;

class HotspotProfiles extends Component
{
    use Processor;

    public $router;

    // Mikrotik field
    #[Validate('required')]
    public $name = '';
    public $id = '';
    public $ippool = 'none';
    public $parentqueue = 'none';
    public $sharedusers = 1;
    public $ratelimit = '';

    // Mikuman fields
    public $price = 0;
    public $sprice = 0;
    public $expmode = 'none';
    public $validity = '';
    public $ulock = 'disable';
    public $slock = 'disable';

    public function mount()
    {
        $this->router = Router::firstWhere('host', session('router'));
        $this->config = [
            'route' => 'hotspot.profiles',
            'getDataUrl' => '/ip/hotspot/user/profile/print',
            'addDataUrl' => '/ip/hotspot/user/profile/add',
            'updateDataUrl' => '/ip/hotspot/user/profile/set',
            'removeDataUrl' => '/ip/hotspot/user/profile/remove'
        ];
        $this->getData();
    }

    public function saveProfile() 
    {
        $this->validate();

        // Mikuman Fields
        $price          = $this->price ?? 0;
        $sprice         = $this->sprice ?? 0;
        $expmode        = $this->expmode ?? 'none';
        $validity       = $this->validity ?? '';
        $ulock          = $this->ulock ?? 'disable';
        $slock          = $this->slock ?? 'disable';

        $mode           = in_array($this->expmode, ['ntf', 'ntfc']) ? 'N' : 'X';
        $recordscript   = in_array($this->expmode, ['remc', 'ntfc']) ? File::get(public_path('/assets/scripts/record')) : '';
        $ulockscript    = $this->ulock != 'Disable' ? File::get(public_path('/assets/scripts/ulock')) : '';
        $slockscript    = $this->slock != 'Disable' ? File::get(public_path('/assets/scripts/slock')) : '';

        $onlogin = !empty($this->expmode) 
            ? File::get(public_path('/assets/scripts/exp'))
            : File::get(public_path('/assets/scripts/noexp'));

        if (!empty($recordscript)) {
            $search         = ['{{name}}','{{price}}','{{validity}}'];
            $replace        = [$this->name,$price,$validity];
            $recordscript   = str_replace($search, $replace , $recordscript);
        }

        $search = (!empty($expmode))
            ? ['expmode','mode','price','sprice','validity','ulock','ulockscript','slock','slockscript','recordscript']
            : ['price','sprice','ulock','ulockscript','slock','slockscript'];
            
        foreach($search as $key) {
            $onlogin = str_replace('{{'.$key.'}}', ${$key}, $onlogin);
        }

        $profile = [
            "name"                  => preg_replace('/\s+/', '-', $this->name),
            "address-pool"          => $this->ippool,
            "rate-limit"            => $this->ratelimit,
            "shared-users"          => $this->sharedusers,
            "parent-queue"          => $this->parentqueue,
            "status-autorefresh"    => "1m",
            "on-login"              => trim(preg_replace('/\s+/', ' ', $onlogin)),
        ];

        if (!empty($this->id)) {
            $profile['.id'] = $this->id;
            $response = Mikrotik::request('/ip/hotspot/user/profile/set', $profile);
        } else {
            $response = Mikrotik::request('/ip/hotspot/user/profile/add', $profile);
        }

        if (!empty($response['!trap'])) {
            $this->error = [
                'message' => $response['!trap'][0]['message']
            ];
        } else {
            $this->getData();
            $this->dispatch('update-data', data: $this->data);
        }
    }

    public function render()
    {
        return view('livewire.hotspot.hotspot-profiles');
    }
}
