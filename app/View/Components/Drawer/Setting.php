<?php

namespace App\View\Components\Drawer;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Router;
use App\Helpers\Mikrotik;

class Setting extends Component
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
        $interfaces = array();
        $response = Mikrotik::request('/interface/print');
        foreach($response as $interface) {
            if (!isset($interface['dynamic'])) {
                array_push($interfaces, $interface['name']);
            }
        }

        $data = [
            'router' => Router::firstWhere('host', session('router')),
            'interfaces' => $interfaces
        ];
        return view('components.drawer.setting', $data);
    }
}
