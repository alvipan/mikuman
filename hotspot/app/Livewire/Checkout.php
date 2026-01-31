<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Voucher;

class Checkout extends Component
{
    public Voucher $voucher;

    public function mount(Voucher $voucher)
    {
        $this->voucher = $voucher;
    }

    public function purchase()
    {
        
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
