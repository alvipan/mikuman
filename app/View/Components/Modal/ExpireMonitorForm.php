<?php

namespace App\View\Components\Modal;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Helpers\Mikrotik;

class ExpireMonitorForm extends Component
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
        $mikuman = Mikrotik::request('/system/scheduler/print', [
            '?name' => 'Mikuman-Expire-Monitor', '?disabled' => 'false'
        ]);

        $mikhmon = Mikrotik::request('/system/scheduler/print', [
            '?name' => 'Mikhmon-Expire-Monitor', '?disabled' => 'false'
        ]);

        $data = [
            'mikuman' => count($mikuman),
            'mikhmon' => count($mikhmon)
        ];
        return view('components.modal.expire-monitor-form', $data);
    }
}
