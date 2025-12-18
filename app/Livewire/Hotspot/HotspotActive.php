<?php

namespace App\Livewire\Hotspot;

use Livewire\Component;
use App\Models\Router;
use App\Helpers\Mikrotik;
use App\Traits\Processor;

class HotspotActive extends Component
{
    use Processor;

    public function mount()
    {
        $this->config = [
            'getDataUrl' => '/ip/hotspot/active/print',
            'removeDataUrl' => '/ip/hotspot/active/remove'
        ];
        $this->getData();
    }

    public function render()
    {
        return view('livewire.hotspot.hotspot-active');
    }
}
