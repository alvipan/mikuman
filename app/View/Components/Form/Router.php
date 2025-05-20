<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Router extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public int $routerId,
    )
    {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $data = [
            'router' => \App\Models\Router::find($this->routerId),
        ];
        return view('components.form.router', $data);
    }
}
