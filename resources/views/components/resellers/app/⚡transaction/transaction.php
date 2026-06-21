<?php

use Livewire\Component;
use App\Models\Transaction;

new class extends Component
{
    protected $listeners = [
        'sale-created' => '$refresh',
    ];
    
    public function getTransactionsProperty()
    {
        return Transaction::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->limit(50)
            ->get();
    }

    public function render()
    {
        return $this->view();
    }
};