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
            ->limit(5)
            ->get();
    }

    public function getPurchasesProperty()
    {
        return Transaction::query()
            ->where('user_id', auth()->id())
            ->where('type', 'purchase')
            ->sum('amount');
    }

    public function getTopupsProperty()
    {
        return Transaction::query()
            ->where('user_id', auth()->id())
            ->where('type', 'topup')
            ->sum('amount');
    }

    public function getOutstandingProperty()
    {
        return max(0, abs($this->purchases) - $this->topups);
    }

    public function render()
    {
        return $this->view();
    }
};