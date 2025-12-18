<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Router;
use App\Helpers\Mikrotik;

class Dashboard extends Component
{
    public $router;
    public $hotspot, $pppoe, $resource, $health, $income, $logs = [];
    public $txBits = 0;
    public $rxBits = 0;

    public function mount() {
        $this->getData();
    }

    public function poll() {
        $this->getResource();
        $this->getHealth();
        $this->getTraffic();
    }

    public function getData() {
        $this->router = Router::firstWhere('host', session('router'));
        $this->hotspot = [
            'users' => count(Mikrotik::request('/ip/hotspot/user/print')),
            'active' => count(Mikrotik::request('/ip/hotspot/active/print'))
        ];
        $this->pppoe = [
            'users' => count(Mikrotik::request('/ppp/secret/print')),
            'active' => count(Mikrotik::request('/ppp/active/print'))
        ];
        $this->income = $this->income();
        $this->logs = $this->logs(8);
        $this->getTraffic();
        $this->getResource();
        $this->getHealth();
    }

    public function getResource()
    {
        $this->resource = Mikrotik::request('/sys/resource/print')[0];
    }

    public function getHealth()
    {
        $this->health = Mikrotik::request('/sys/health/print');
    }

    public function income() {
        $data = [
            'today' => 0,
            'yesterday' => 0,
            'this-month' => 0,
            'last-month' => 0
        ];
        $response = Mikrotik::request('/sys/script/print');

        foreach($response as $item) {
            $content = explode('-|-', $item['name']);

            $today = strtolower(date("M/d/Y"));
            $yesterday = strtolower(date("M/d/Y", strtotime('-1 day')));
            $thisMonth = strtolower(date("MY"));
            $lastMonth = strtolower(date("MY", strtotime('-1 month')));

            if ($item['source'] == $today) {
                $data['today'] += $content[3];
            }

            if ($item['source'] == $yesterday) {
                $data['yesterday'] += $content[3];
            }

            if ($item['owner'] == $thisMonth) {
                $data['this-month'] += $content[3];
            }

            if ($item['owner'] == $lastMonth) {
                $data['last-month'] += $content[3];
            }
        }

        return [
            'today' => number_format($data['today'], 0),
            'yesterday' => number_format($data['yesterday'], 0),
            'this-month' => number_format($data['this-month'], 0),
            'last-month' => number_format($data['last-month'], 0)
        ];
    }

    public function getTraffic() {
        $router = Router::firstWhere('host', session('router'));
        if (empty($router->interface)) {
            return 0;
        }
        
        $response = Mikrotik::request('/interface/monitor-traffic', ['interface' => $router->interface, 'once' => ''], 'equal');
        $this->txBits = $response[0]['tx-bits-per-second'];
        $this->rxBits = $response[0]['rx-bits-per-second'];
        $this->dispatch('traffic-updated');
    }

    public function logs($limit = null) {
        $arr = array();
        $item = array();
        $response = Mikrotik::request('/log/print', ['?topics' => 'hotspot,info,debug']);

        for ($i = 0; $i < ($limit ?? count($response)); $i++) {
            $row = $response[$i];
            if (str_contains($row['message'], '->')) {
                $item = [
                    'time' => $row['time'],
                    'type' => in_array('pppoe', $row) ? 'PPPoE' : 'Hotspot',
                    'user' => $this->logUser($row),
                    'mac' => '',
                    'ip' => $this->logIp($row),
                    'message' => $this->logMessage($row)
                ];
                $arr[] = $item;
            }
        }

        return array_reverse($arr);
    }

    function logUser($data) {
        if (in_array('pppoe', $data)) {
            $x = str_replace(['<', '>'], '', $data['message']);
            return explode('-', explode(': ', $x)[0])[1];
        }
        $x = explode(': ', $data['message']);
        return $x[0] != '->' ? explode(' ', $x[0])[0] : explode(' ', $x[1])[0];
    }

    function logIp($data) {
        if (in_array('pppoe', $data)) {
            return '';
        }
        $x = explode(': ', $data['message']);
        return $x[0] != '->' 
            ? str_replace(['(',')'], '', explode(' ', $x[0])[1])
            : str_replace(['(',')'], '', explode(' ', $x[1])[1]);
    }

    function logMessage($data) {
        if (in_array('pppoe', $data)) {
            $x = str_replace(['<','>'], '', $data['message']);
            return explode(': ')[1];
        }
        return explode('): ', $data['message'])[1];
    }

    function formatBytes($size, $precision = 0){
        $unit = ['Byte','KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];

        for($i = 0; $size >= 1024 && $i < count($unit)-1; $i++){
            $size /= 1024;
        }

        return round($size, $precision).' '.$unit[$i];
    }

    public function render() 
    {
        return view('livewire.dashboard', [
            'cpu_frequency' => $this->resource['cpu-frequency'],
            'cpu_load' => $this->resource['cpu-load'],
            'total_memory' => $this->formatBytes($this->resource['total-memory']),
            'free_memory' => $this->formatBytes($this->resource['free-memory']),
            'memory_usage' => (100/$this->resource['total-memory'])*($this->resource['total-memory']-$this->resource['free-memory']),
            'total_hdd_space' => $this->formatBytes($this->resource['total-hdd-space']),
            'free_hdd_space' => $this->formatBytes($this->resource['free-hdd-space']),
            'hdd_usage' => (100/$this->resource['total-hdd-space'])*($this->resource['total-hdd-space']-$this->resource['free-hdd-space'])
        ]);
    
    }
}
