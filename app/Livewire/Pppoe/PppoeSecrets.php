<?php

namespace App\Livewire\Pppoe;

use Livewire\Component;
use App\Traits\Processor;

class PppoeSecrets extends Component
{
    use Processor;

    public function mount()
    {
        $this->config = [
            'getDataUrl' => '/ppp/secret/print',
            'addDataUrl' => '/ppp/secret/add',
            'updateDataUrl' => '/ppp/secret/set',
            'removeDataUrl' => '/ppp/secret/remove',
            'tableHeader' => ['checkbox','name','rate limit','only one','dns server','default','action']
        ];

        $this->getData();
    }

    public function render()
    {
        return view('livewire.pppoe.pppoe-secrets');
    }
}
