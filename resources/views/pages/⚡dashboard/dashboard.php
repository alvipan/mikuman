<?php

use Livewire\Component;
use Livewire\Attributes\Title;

new #[Title('Dashboard')] class extends Component
{
    public function render()
    {
        return $this->view();
    }
};
