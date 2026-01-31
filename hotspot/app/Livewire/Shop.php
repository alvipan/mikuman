<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Voucher;

class Shop extends Component
{
    public $vouchers = array();

    public function mount()
    {
        $this->vouchers = Voucher::all();
    }

    public function render()
    {
        return view('livewire.shop');
    }
}
