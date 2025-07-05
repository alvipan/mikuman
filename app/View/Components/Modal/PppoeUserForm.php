<?php

namespace App\View\Components\Modal;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Helpers\Mikrotik;

class PppoeUserForm extends Component
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
            'profiles' => Mikrotik::request('/ppp/profile/print')
        ];
        return view('components.modal.pppoe-user-form', $data);
    }
}
