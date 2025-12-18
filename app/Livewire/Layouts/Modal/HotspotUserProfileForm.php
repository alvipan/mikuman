<?php

namespace App\Livewire\Layouts\Modal;

use Illuminate\Support\Facades\file;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Helpers\Mikrotik;

class HotspotUserProfileForm extends Component
{
    public $pools = [];
    public $queues = [];
    public $profile = [];

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

    public function mount() {
        $this->pools = Mikrotik::request('/ip/pool/print');
        $this->queues = Mikrotik::request('/queue/simple/print', ['?dynamic' => 'false']);
    }

    #[On('set-profile')]
    public function setProfile($profile) {
        $this->id = $profile['id'];
        $this->name = $profile['name'];
        $this->ippool = $profile['ip-pool'];
        $this->ratelimit = $profile['rate-limit'];
        $this->sharedusers = $profile['shared-users'];
        $this->parentqueue = $profile['parent-queue'];
        $this->expmode = $profile['expire-mode'];
        $this->price = $profile['price'];
        $this->sprice = $profile['sprice'];
        $this->validity = $profile['validity'];
        $this->slock = $profile['lock-server'];
        $this->ulock = $profile['lock-users'];
        $this->js("HSOverlay.open('#hotspot-user-profile-form-modal')");
    }

    #[On('reset-profile')]
    public function resetProfile() {
        $this->reset();
    }

    public function save() 
    {
        $this->validate();

        // Mikuman Fields
        $price          = !empty($this->price) ? $this->price : 0;
        $sprice         = !empty($this->sprice) ? $this->sprice : 0;
        $expmode        = !empty($this->expmode) ? $this->expmode : 'none';
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
            $this->reset();
            $this->dispatch('profileAdded');
        }
    }

    public function render()
    {
        return view('livewire.layouts.modal.hotspot-user-profile-form');
    }
}
