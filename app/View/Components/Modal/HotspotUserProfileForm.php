<?php

namespace App\View\Components\Modal;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Helpers\Mikrotik;

class HotspotUserProfileForm extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $data = [
            'pools' => Mikrotik::request('/ip/pool/print'),
            'queues' => Mikrotik::request("/queue/simple/print", array("?dynamic" => "false",))
        ];
        return view('components.modal.hotspot-user-profile-form', $data);
    }
}
