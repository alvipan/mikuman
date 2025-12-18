<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Router;
use App\Helpers\Mikrotik;

class Report extends Component
{
    public $router;
    public $reports = [];

    public function mount()
    {
        $this->router = Router::firstWhere('host', session('router'));
        $this->getReports();
    }

    public function getReports() {
        $response = Mikrotik::request('/sys/script/print');
        $reports = [];

        foreach($response as $x) {
            $data = explode('-|-', $x['name']);
            $item = [
                'date' => ucfirst($data[0]),
                'time' => $data[1],
                'user' => $data[2],
                'price' => $data[3],
                'ip-address' => $data[4],
                'mac-address' => $data[5],
                'profile' => $data[7],
                'comment' => $data[8],
            ];
            $reports[] = $item;
        }
        $this->reports = array_reverse($reports);
    }

    public function render()
    {
        return view('livewire.report');
    }
}
