<?php

namespace App\View\Components\Part;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Router;

class Header extends Component
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
            'router' => Router::firstWhere('host', session('router'))
        ];
        return view('components.part.header', $data);
    }
}
