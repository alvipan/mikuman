<?php

namespace App\Livewire\Pppoe;

use Livewire\Component;
use App\Traits\Processor;

class PppoeProfiles extends Component
{
    use Processor;

    public $id = '';

    public function mount()
    {
        $this->config = [
            'getDataUrl' => '/ppp/profile/print',
            'addDataUrl' => '/ppp/profile/add',
            'updateDataUrl' => '/ppp/profile/set',
            'removeDataUrl' => '/ppp/profile/remove',
            'tableHeader' => ['checkbox','name','rate limit','only one','dns server','default','action']
        ];

        $this->item = [
            'name' => '',
            'rate-limit' => '',
            'dns-server' => '',
            'parent-queue' => '',
            'only-one' => ''
        ];

        $this->getData();
    }

    private function rateIsValid($input) {
        return preg_match('/[1-9][kM]\/[1-9][kM]/', $input);
    }

    public function render()
    {
        return view('livewire.pppoe.pppoe-profiles');
    }
}
