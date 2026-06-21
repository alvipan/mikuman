<?php
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;

new class extends Component
{
    #[Url(except: 'dashboard')] 
    public string $tab = 'dashboard';


    #[On('set-tab')] 
    public function setTab(string $tab)
    {
        $this->tab = $tab;
    }

    public function render()
    {
        return $this->view()->layout('layouts::reseller')->title('Mikuman');
    }
};