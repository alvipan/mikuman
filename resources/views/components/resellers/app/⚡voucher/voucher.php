<?php

use Livewire\Component;
use App\Models\SaleItem;

new class extends Component
{
    protected $listeners = [
        'sale-created' => '$refresh',
    ];
    
    public function getVouchersProperty()
    {
        return SaleItem::query()
            ->with(['voucher','profile'])
            ->whereHas('sale', fn($q) =>
                $q->where('user_id', auth()->id())
            )
            ->latest()
            ->limit(50)
            ->get();
    }

    public function render()
    {
        return $this->view();
    }
};