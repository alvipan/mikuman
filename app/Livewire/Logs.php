<?php

namespace App\Livewire;

use Livewire\Component;
use App\Helpers\Mikrotik;

class Logs extends Component
{
    public $logs = [];

    public function mount() {
        $logs = [];
        $response = Mikrotik::request('/log/print', ['?topics' => 'hotspot,info,debug']);

        foreach ($response as $log) {
            if (str_contains($log['message'], '->')) {
                $item = [
                    'time' => $log['time'],
                    'type' => in_array('pppoe', $log) ? 'PPPoE' : 'Hotspot',
                    'user' => $this->getUser($log),
                    'mac' => '',
                    'ip' => $this->getIP($log),
                    'message' => $this->getMessage($log)
                ];
                $logs[] = $item;
            }
        }

        $this->logs = array_reverse($logs);
    }

    private function getUser($data) {
        if (in_array('pppoe', $data)) {
            $x = str_replace(['<', '>'], '', $data['message']);
            return explode('-', explode(': ', $x)[0])[1];
        }
        $x = explode(': ', $data['message']);
        return $x[0] != '->' ? explode(' ', $x[0])[0] : explode(' ', $x[1])[0];
    }

    private function getIP($data) {
        if (in_array('pppoe', $data)) {
            return '';
        }
        $x = explode(': ', $data['message']);
        return $x[0] != '->' 
            ? str_replace(['(',')'], '', explode(' ', $x[0])[1])
            : str_replace(['(',')'], '', explode(' ', $x[1])[1]);
    }

    private function getMessage($data) {
        if (in_array('pppoe', $data)) {
            $x = str_replace(['<','>'], '', $data['message']);
            return explode(': ')[1];
        }
        return explode('): ', $data['message'])[1];
    }

    public function render()
    {
        return view('livewire.logs');
    }
}
