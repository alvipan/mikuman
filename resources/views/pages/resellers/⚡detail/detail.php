<?php

use App\Models\User;
use App\Models\HotspotUser;
use App\Models\ResellerCommission;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

new class extends Component
{
    use WithPagination, WithoutUrlPagination;

    public User $reseller;

    #[Url(except: 'overview')]
    public string $tab = 'overview';

    public bool $showPayoutModal = false;
    public int $payoutAmount = 0;
    public string $payoutNotes = '';

    public array $form = [];

    public function mount(User $reseller)
    {
        abort_if(
            $reseller->role !== 'reseller',
            404
        );

        $this->reseller = $reseller;

        $this->form = [
            'name' => $reseller->name,
            'username' => $reseller->username,
        ];
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;

        $this->resetPage();
    }

    public function updateReseller(): void
    {
        $this->validate([
            'form.name' => ['required', 'string', 'max:255'],
            'form.username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username,' . $this->reseller->id,
            ],
        ]);

        $this->reseller->update($this->form);

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Reseller updated'
        );
    }

    public function getUnusedVouchersProperty(): int
    {
        return $this->reseller
            ->vouchers()
            ->where('status', 'unused')
            ->count();
    }

    public function getUsedVouchersProperty(): int
    {
        return $this->reseller
            ->vouchers()
            ->where('status', 'used')
            ->count();
    }

    public function getTotalEarnedProperty(): float
    {
        return $this->reseller
            ->commissions()
            ->where('type', 'earn')
            ->sum('amount');
    }

    public function getTotalPaidProperty(): float
    {
        return abs(
            $this->reseller
                ->commissions()
                ->where('type', 'payout')
                ->sum('amount')
        );
    }

    public function getOutstandingProperty(): float
    {
        return $this->reseller
            ->commissions()
            ->sum('amount');
    }

    public function getVouchersProperty()
    {
        return $this->reseller
            ->vouchers()
            ->latest()
            ->paginate(10);
    }

    public function getCommissionsProperty()
    {
        return $this->reseller
            ->commissions()
            ->latest()
            ->paginate(10);
    }

    public function openPayout(): void
    {
        $this->reset([
            'payoutAmount',
            'payoutNotes',
        ]);

        $this->showPayoutModal = true;
    }

    public function submitPayout(): void
    {
        $this->validate([
            'payoutAmount' => [
                'required',
                'integer',
                'min:1',
            ],
            'payoutNotes' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        if ($this->payoutAmount > $this->outstanding) {

            $this->addError(
                'payoutAmount',
                'Amount exceeds outstanding commission.'
            );

            return;
        }

        $this->reseller->commissions()->create([
            'type'       => 'payout',
            'amount'     => -$this->payoutAmount,
            'notes'      => $this->payoutNotes,
            'created_by' => auth()->id(),
        ]);

        $this->reseller->refresh();

        $this->showPayoutModal = false;

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Commission paid successfully'
        );
    }

    public function render()
    {
        return $this->view([
            'vouchers' => $this->vouchers,
            'commissions' => $this->commissions,
        ])->title('Detail Reseller');
    }
};