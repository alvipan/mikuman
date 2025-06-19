<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\file;
use App\Helpers\Mikrotik;

class POSTController extends Controller
{
    function expireMonitor(Request $request) {
        $script = File::get(public_path('/assets/scripts/expmon'));

        $monitor = Mikrotik::request('/system/scheduler/print', [
            '?name' => 'Mikhmon-Expire-Monitor'
        ]);

        if (count($monitor) > 0 && !empty($request->mikhmon)) {
            if ($request->mikhmon == 'disable') {
                Mikrotik::request('/system/scheduler/set', [
                    '.id'       => $monitor[0]['.id'],
                    'disabled'  => 'true'
                ]);
            }
            if ($request->mikhmon == 'remove') {
                Mikrotik::request('/system/scheduler/remove', [
                    '.id'       => $monitor[0]['.id']
                ]);
            }
        }

        $monitor = Mikrotik::request('/system/scheduler/print', [
            '?name' => 'Mikuman-Expire-Monitor'
        ]);

        if (count($monitor) > 0 && $monitor[0]['disabled'] == true) {
            $response = Mikrotik::request('/system/scheduler/set', [
                '.id'       => $monitor[0]['.id'],
                'interval'  => '00:01:00',
                'on-event'  => trim(preg_replace('/\s+/', ' ', $script)),
                'disabled'  => 'no'
            ]);
        } else {
            $response = Mikrotik::request('/system/scheduler/add', [
                'name'          => 'Mikuman-Expire-Monitor',
                'start-time'    => '00:00:00',
                'interval'      => '00:01:00',
                'on-event'      => trim(preg_replace('/\s+/', ' ', $script)),
                'disabled'      => 'no',
                'comment'       => 'Mikuman Expire Monitor'
            ]);
        }

        return [
            'success' => true,
            'message' => 'Mikuman expire monitor has been setup.'
        ];
    }
}
