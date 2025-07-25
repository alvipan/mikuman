<?php

namespace App\View\Components\Modal;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Helpers\Mikrotik;

class HotspotUserEditForm extends Component
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
            'servers' => Mikrotik::request('/ip/hotspot/print'),
            'profiles' => Mikrotik::request('/ip/hotspot/user/profile/print')
        ];
        return view('components.modal.hotspot-user-edit-form', $data);
    }
}
