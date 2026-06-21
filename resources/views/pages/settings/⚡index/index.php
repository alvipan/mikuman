<?php

use Livewire\Component;
use App\Services\Settings\SettingService;

new class extends Component
{
    public string $currencyCode = 'IDR';
    public string $currencySymbol = 'Rp';
    public string $timezone = 'Asia/Jakarta';

    public array $currencies = [
        'IDR' => 'Rp',
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'JPY' => '¥',
        'CNY' => '¥',
        'SGD' => 'S$',
        'MYR' => 'RM',
        'THB' => '฿',
        'AUD' => 'A$',
        'CAD' => 'C$',
    ];

    public function mount(): void
    {
        $this->currencyCode = setting('currency_code', 'IDR');
        $this->currencySymbol = setting('currency_symbol', 'Rp');
        $this->timezone = setting('timezone', 'Asia/Jakarta');
    }

    public function updatedCurrencyCode(string $value): void
    {
        $this->currencySymbol = $this->currencies[$value] ?? '';
    }

    public function getTimezonePreviewProperty(): string
    {
        return now()
            ->timezone($this->timezone)
            ->format('d M Y H:i');
    }

    public function save(): void
    {
        $this->validate([
            'currencyCode' => ['required', 'string'],
            'currencySymbol' => ['required', 'string'],
            'timezone' => ['required', 'string'],
        ]);

        app(SettingService::class)->set('currency_code', $this->currencyCode);
        app(SettingService::class)->set('currency_symbol', $this->currencySymbol);
        app(SettingService::class)->set('timezone', $this->timezone);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Settings saved successfully.',
        ]);
    }

    public function render()
    {
        return $this->view()->title('Settings');
    }
};