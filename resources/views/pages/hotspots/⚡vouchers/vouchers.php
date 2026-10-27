<?php

use App\Models\Package;
use App\Models\Reseller;
use App\Models\Voucher;
use App\Services\RadiusService;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Hotspot Vouchers')] class extends Component
{
    public $vouchers;
    public $packages;
    public $resellers;

    // Form state
    public $package_id = '';
    public $reseller_id = '';
    public $quantity = 1;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->vouchers = Voucher::with(['package', 'reseller'])->latest()->take(50)->get(); // Batasi 50 untuk performa
        $this->packages = Package::where('type', 'hotspot')->where('is_active', true)->get();
        $this->resellers = Reseller::orderBy('name')->get();
    }

    public function generateVouchers()
    {
        $this->validate([
            'package_id' => 'required|exists:packages,id',
            'reseller_id' => 'nullable|exists:resellers,id',
            'quantity' => 'required|integer|min:1|max:100', // Batasi maksimal 100 per generate
        ]);

        $package = Package::findOrFail($this->package_id);
        $radius = new RadiusService();

        // Pastikan paket sudah di-sync ke RADIUS (radgroupreply)
        $radius->syncPackage($package);

        for ($i = 0; $i < $this->quantity; $i++) {
            // Generate kode voucher acak (misal: 8 karakter unik)
            $code = strtoupper(substr(uniqid(), -8));

            // Simpan ke database Mikuman
            $voucher = Voucher::create([
                'code' => $code,
                'package_id' => $this->package_id,
                'reseller_id' => $this->reseller_id ?: null,
                'status' => 'unused',
                'sale_price' => $package->sale_price,
                'cost_price' => $package->cost_price,
            ]);

            // Sinkronisasi ke FreeRADIUS (radcheck & radusergroup)
            // Untuk hotspot, password = kode voucher
            $radius->syncUser($voucher->code, $voucher->code, $package->name);
        }

        $this->reset('quantity');
        $this->loadData();
        $this->dispatch('notify', message: "{$this->quantity} Voucher berhasil dibuat dan disinkronkan.", type: 'success');
    }

    public function deleteVoucher($id)
    {
        $voucher = Voucher::findOrFail($id);

        // Hapus dari FreeRADIUS juga
        $radius = new RadiusService();
        $radius->deleteUser($voucher->code);

        $voucher->delete();
        $this->loadData();
        $this->dispatch('notify', message: 'Voucher berhasil dihapus.', type: 'success');
    }
};
