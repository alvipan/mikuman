<?php

namespace App\Livewire\Pages\Routers\Index;

use App\Models\Router;
use App\Models\Setting;
use App\Services\Mikrotik\MikrotikService;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Routers')] class extends Component
{
    public $routers;
    public array $statuses = [];

    // Form State
    public ?int $routerId = null;
    public string $name = '', $address = '', $protocol = 'https', $username = '', $password = '';
    public int $port = 443;
    public bool $is_active = true;
    public string $lan_ip = '192.168.88.1', $lan_interface = 'bridge-lan', $wlan_ssid = '', $hotspot_subnet = '10.10.0.0/16', $pppoe_subnet = '10.20.0.0/16';

    // Setup Modal State
    public bool $showSetupModal = false;
    public ?int $setupRouterId = null;
    public string $setupRouterName = '';
    public bool $isProvisioning = false;
    public bool $setupFinished = false;
    public array $provisionSteps = [];
    public string $provisionError = '';

    // State Machine untuk Provisioning
    public int $currentStep = 0;
    public array $stepList = [];

    public function mount()
    {
        $this->loadRouters();
    }

    public function loadRouters()
    {
        $this->routers = Router::latest()->get();
        if ($this->routers->isNotEmpty()) $this->statuses = MikrotikService::getStatuses($this->routers);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string',
            'address' => 'required|ip',
            'port' => 'required|integer',
            'username' => 'required',
            'password' => 'required',
            'lan_ip' => 'required|ip',
            'lan_interface' => 'required',
            'hotspot_subnet' => 'required',
            'pppoe_subnet' => 'required'
        ]);

        Router::updateOrCreate(['id' => $this->routerId], [
            'name' => $this->name,
            'address' => $this->address,
            'protocol' => $this->protocol,
            'port' => $this->port,
            'username' => $this->username,
            'password' => $this->password,
            'is_active' => $this->is_active,
            'lan_ip' => $this->lan_ip,
            'lan_interface' => $this->lan_interface,
            'wlan_ssid' => $this->wlan_ssid,
            'hotspot_subnet' => $this->hotspot_subnet,
            'pppoe_subnet' => $this->pppoe_subnet,
        ]);

        $this->resetForm();
        $this->loadRouters();
        $this->dispatch('notify', message: 'Router disimpan.', type: 'success');
    }

    public function edit(int $id)
    {
        $r = Router::findOrFail($id);
        $this->routerId = $r->id;
        $this->name = $r->name;
        $this->address = $r->address;
        $this->protocol = $r->protocol;
        $this->port = $r->port;
        $this->username = $r->username;
        $this->password = $r->password;
        $this->is_active = $r->is_active;
        $this->lan_ip = $r->lan_ip;
        $this->lan_interface = $r->lan_interface;
        $this->wlan_ssid = $r->wlan_ssid;
        $this->hotspot_subnet = $r->hotspot_subnet;
        $this->pppoe_subnet = $r->pppoe_subnet;
    }

    public function delete(int $id)
    {
        Router::findOrFail($id)->delete();
        $this->loadRouters();
        $this->dispatch('notify', message: 'Router dihapus.', type: 'error');
    }

    public function resetForm()
    {
        $this->reset(['routerId', 'name', 'address', 'username', 'password', 'wlan_ssid']);
        $this->protocol = 'https';
        $this->port = 443;
        $this->is_active = true;
        $this->lan_ip = '192.168.88.1';
        $this->lan_interface = 'bridge-lan';
        $this->hotspot_subnet = '10.10.0.0/16';
        $this->pppoe_subnet = '10.20.0.0/16';
        $this->resetValidation();
    }

    public function confirmSetup(int $id)
    {
        $router = Router::findOrFail($id);
        $this->setupRouterId = $id;
        $this->setupRouterName = $router->name;
        $this->provisionSteps = [];
        $this->provisionError = '';
        $this->setupFinished = false;
        $this->isProvisioning = false;
        $this->currentStep = 0;
        $this->showSetupModal = true;
    }

    public function closeSetupModal()
    {
        $this->showSetupModal = false;
        $this->provisionSteps = [];
        $this->provisionError = '';
    }

    public function startSetup()
    {
        $this->isProvisioning = true;
        $this->setupFinished = false;
        $this->provisionSteps = [];
        $this->currentStep = 0;

        $this->stepList = [
            'Cek Router',
            'Buat Bridge',
            'Buat IP Pool',
            'Setup Hotspot',
            'Setup PPPoE',
            'Setup RADIUS',
            'Setup WLAN'
        ];

        // Tampilkan step pertama sebagai 'running'
        $this->provisionSteps[] = ['step' => $this->stepList[0], 'status' => 'running'];

        // Panggil proses via JavaScript setelah 1 detik agar modal sempat render
        $this->dispatch('execute-step');
    }

    #[\Livewire\Attributes\On('execute-step')]
    public function executeStep()
    {
        if (!$this->isProvisioning || $this->currentStep >= count($this->stepList)) return;

        $stepName = $this->stepList[$this->currentStep];
        $router = Router::findOrFail($this->setupRouterId);
        $mikrotik = new MikrotikService($router);

        try {
            // Eksekusi berdasarkan urutan index
            switch ($this->currentStep) {
                case 0:
                    $mikrotik->stepCheckRouter();
                    break;
                case 1:
                    $mikrotik->stepSetupBridge($router->lan_interface);
                    break;
                case 2:
                    $mikrotik->stepSetupPool($router->hotspot_subnet, $router->pppoe_subnet, $router->lan_ip);
                    break;
                case 3:
                    $mikrotik->stepSetupHotspot($router->lan_interface);
                    break;
                case 4:
                    $mikrotik->stepSetupPppoe($router->lan_interface);
                    break;
                case 5:
                    $mikrotik->stepSetupRadius(Setting::get('radius_server_ip'), Setting::get('radius_default_secret'));
                    break;
                case 6:
                    $mikrotik->stepSetupWlan($router->wlan_ssid);
                    break;
            }

            // Ubah status step saat ini jadi sukses
            $lastIndex = count($this->provisionSteps) - 1;
            $this->provisionSteps[$lastIndex]['status'] = 'success';

            // Siapkan step berikutnya
            $this->currentStep++;

            if ($this->currentStep < count($this->stepList)) {
                // Tambahkan step berikutnya sebagai 'running'
                $this->provisionSteps[] = ['step' => $this->stepList[$this->currentStep], 'status' => 'running'];
                // Panggil lagi 1 detik kemudian
                $this->dispatch('execute-step');
            } else {
                // Selesai semua
                $this->isProvisioning = false;
                $this->setupFinished = true;
            }
        } catch (\Throwable $e) {
            // Tandai gagal
            $lastIndex = count($this->provisionSteps) - 1;
            $this->provisionSteps[$lastIndex]['status'] = 'failed';
            $this->provisionSteps[$lastIndex]['error'] = $e->getMessage();

            $this->provisionError = "Gagal di langkah {$stepName}: " . $e->getMessage();
            $this->isProvisioning = false;
            $this->setupFinished = true;
        }
    }
};
