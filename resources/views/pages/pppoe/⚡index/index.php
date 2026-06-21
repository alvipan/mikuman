<?php

use Livewire\Component;
use Livewire\Attributes\Url;

new class extends Component
{
    #[Url(except: 'customers')]
    public $tab = 'customers';

    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    public function render()
    {
        return $this->view()->title('PPPoE');
    }
};