<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Facades\DB;
use App\Models\Router;
use App\Models\User;
use App\Models\HotspotProfile;
use App\Models\HotspotUser;
use App\Services\Hotspot\HotspotUserService;

new class extends Component {

    use WithPagination, WithoutUrlPagination;

    public $editId = null;
    public $name;
    public $password;
    public $expired_at;

    public $router_id;
    public $profile_id;
    public $reseller_id;
    public $qty = 1;

    public bool $showGenerateModal = false;
    public bool $showEditModal = false;
    public bool $showPrintModal = false;

    public $selectedBatch;
    public $selectedColor = 'blue';
    public $showQr = true;

    public $search = '';
    public $filterRouter = '';
    public $filterProfile = '';
    public $filterReseller = '';
    public $filterStatus = '';

    public $routers = [];
    public $profiles = [];
    public $resellers = [];
    public $batches = [];

    public function mount()
    {
        $this->routers = Router::orderBy('name')->get();

        $this->profiles = HotspotProfile::orderBy('name')->get();

        $this->resellers = User::where('role', 'reseller')
            ->orderBy('name')
            ->get();
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'filterRouter',
            'filterProfile',
            'filterReseller',
            'filterStatus',
        ]);

        $this->resetPage();
    }

    public function updatedRouterId()
    {
        $this->profiles = HotspotProfile::where(
            'router_id',
            $this->router_id
        )->get();
    }

    public function openGenerateModal()
    {
        $this->reset([
            'router_id',
            'profile_id',
            'reseller_id',
            'qty'
        ]);

        $this->qty = 1;

        $this->showGenerateModal = true;
    }

    public function generate()
    {
        $this->validate([
            'router_id' => 'required|exists:routers,id',
            'profile_id' => 'required|exists:hotspot_profiles,id',
            'reseller_id' => 'nullable|exists:users,id',
            'qty' => 'required|integer|min:1|max:200',
        ]);

        $router = Router::findOrFail($this->router_id);
        $profile = HotspotProfile::findOrFail($this->profile_id);

        $hotspot = new HotspotUserService($router);
        $voucherService = new \App\Services\Voucher\VoucherService();

        // batch auto
        $batch = 'BCH-' . now()->format('YmdHis');

        DB::transaction(function () use ($hotspot, $voucherService, $profile, $batch) {

            $codes = $voucherService->generateBatch(
                $this->qty,
                $profile->code_length
            );

            foreach ($codes as $code) {

                // create di mikrotik
                $res = $hotspot->create([
                    'name' => $code,
                    'password' => $code,
                    'profile' => $profile->name,
                ]);

                // simpan DB
                HotspotUser::create([
                    'router_id' => $this->router_id,
                    'profile_id' => $this->profile_id,
                    'reseller_id' => $this->reseller_id,
                    'mikrotik_id' => $res['.id'] ?? null,

                    'username' => $code,
                    'password' => $code,

                    'batch' => $batch,

                    // optional snapshot (recommended)
                    'sale_price' => $profile->sale_price ?? null,
                    'cost_price' => $profile->cost_price ?? null,
                ]);
            }
        });

        $this->showGenerateModal = false;
    }

    public function loadVouchers()
    {
        return HotspotUser::query()
            ->with([
                'router:id,name',
                'profile:id,name',
                'reseller:id,name',
            ])

            ->when($this->search, function ($query) {

                $query->where(function ($q) {

                    $q->where('username', 'like', '%' . $this->search . '%')
                        ->orWhere('password', 'like', '%' . $this->search . '%')
                        ->orWhere('batch', 'like', '%' . $this->search . '%');

                });

            })

            ->when($this->filterRouter, function ($query) {

                $query->where('router_id', $this->filterRouter);

            })

            ->when($this->filterProfile, function ($query) {

                $query->where('profile_id', $this->filterProfile);

            })

            ->when($this->filterReseller, function ($query) {

                $query->where('reseller_id', $this->filterReseller);

            })

            ->when($this->filterStatus, function ($query) {

                $query->where('status', $this->filterStatus);

            })

            ->latest()
            ->paginate(10);
    }

    public function loadBatches()
    {
        $this->batches = HotspotUser::query()
            ->select('batch')
            ->distinct()
            ->latest()
            ->pluck('batch');
    }

    public function openPrintModal()
    {
        $this->loadBatches();

        $this->reset([
            'selectedBatch',
            'selectedColor'
        ]);

        $this->selectedColor = 'blue';

        $this->showPrintModal = true;
    }

    public function print()
    {
        $this->validate([
            'selectedBatch' => 'required',
            'selectedColor' => 'required',
        ]);

        $url = route('vouchers.print', [
            'batch' => $this->selectedBatch,
            'color' => $this->selectedColor,
            'qr' => $this->showQr ? 1 : 0,
        ]);

        $this->dispatch('print-voucher', url: $url);
    }

    public function edit($id)
    {
        $voucher = HotspotUser::findOrFail($id);

        $this->editId = $voucher->id;
        $this->name = $voucher->username;
        $this->password = $voucher->password;
        $this->expired_at = $voucher->expired_at
            ? $voucher->expired_at->format('Y-m-d\TH:i')
            : null;

        $this->showEditModal = true;
    }

    public function update()
    {
        // 1. Validasi input form modal
        $this->validate([
            'name' => 'required|string|max:100',
            'password' => 'required|string|max:100',
            'expired_at' => 'nullable|date',
        ]);

        // 2. Proteksi jika editId hilang/null untuk mencegah 404 global
        if (!$this->editId) {
            return;
        }

        $voucher = HotspotUser::with('router')
            ->findOrFail($this->editId);

        DB::transaction(function () use ($voucher) {

            $hotspot = new HotspotUserService($voucher->router);

            if ($voucher->mikrotik_id) {
                $hotspot->update($voucher->mikrotik_id, [
                    'name' => $this->name,
                    'password' => $this->password,
                ]);
            }

            $voucher->update([
                'username' => $this->name,
                'password' => $this->password,
                'expired_at' => $this->expired_at,
            ]);
        });

        $this->showEditModal = false;
        $this->dispatch('notify', type: 'success', message: 'Voucher updated successfully');

        $this->reset([
            'editId',
            'name',
            'password',
            'expired_at'
        ]);
    }

    public function updated($property)
    {
        if (in_array($property, [
            'search',
            'filterRouter',
            'filterProfile',
            'filterReseller',
            'filterStatus',
        ])) {
            $this->resetPage();
        }
    }

    public function delete($id)
    {
        $voucher = HotspotUser::with('router')->findOrFail($id);

        DB::transaction(function () use ($voucher) {

            $hotspot = new HotspotUserService($voucher->router);

            // delete user di router
            $hotspot->delete($voucher->mikrotik_id);

            // delete DB
            $voucher->delete();

        });
    }

    public function render()
    {
        return $this->view([
            'vouchers' => $this->loadVouchers()
        ]);
    }
};