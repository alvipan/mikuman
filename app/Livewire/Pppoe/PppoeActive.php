<?php

namespace App\Livewire\Pppoe;

use Livewire\Component;
use App\Traits\Processor;

class PppoeActive extends Component
{
    use Processor;

    public function mount()
    {
        $this->config = [
            'getDataUrl' => '/ppp/active/print',
            'removeDataUrl' => '/ppp/active/remove',
            'tableHeader' => ['checkbox','service','name','caller-id','address','uptime','action']
        ];

        $this->getData();
    }

    public function render()
    {
        return view('livewire.pppoe.pppoe-active');
    }
}
