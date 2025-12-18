<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Mikrotik;
use App\Models\Router;

class GETController extends Controller
{
    public function expireMonitor(Request $request) {
        $mikuman = Mikrotik::request('/system/scheduler/print', [
            '?name' => 'Mikuman-Expire-Monitor', '?disabled' => 'false'
        ]);

        $mikhmon = Mikrotik::request('/system/scheduler/print', [
            '?name' => 'Mikhmon-Expire-Monitor', '?disabled' => 'false'
        ]);

        $result = [
            'success' => true,
            'data' => [
                'mikuman' => count($mikuman),
                'mikhmon' => count($mikhmon)
            ]
        ];

        return $result;
    }
}
