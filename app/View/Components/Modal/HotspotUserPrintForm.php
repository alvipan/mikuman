<?php

namespace App\View\Components\Modal;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Helpers\Mikrotik;

class HotspotUserPrintForm extends Component
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
        $users = Mikrotik::request('/ip/hotspot/user/print');
        $data = [
            'comments' => array()
        ];
        foreach($users as $user) {
            if (
                isset($user['comment']) &&
                str_contains($user['comment'], 'vc-') &&
                !in_array($user['comment'], $data['comments'])
            ) {
                array_push($data['comments'], $user['comment']);
            }
        }
        return view('components.modal.hotspot-user-print-form', $data);
    }
}
