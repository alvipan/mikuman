<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Helpers\Mikrotik;
use App\Models\Router;

class HotspotController extends Controller
{
    public function profiles(Request $request) {
        $data = [
            'router' => Router::firstWhere('host', session('router')),
            'menu' => 'hotspot',
            'submenu' => 'hotspot-profiles'
        ];
        return view('pages.hotspot-profiles', $data);
    }

    public function users(Request $request) {
        $data = [
            'menu' => 'hotspot',
            'submenu' => 'hotspot-users'
        ];
        return view('pages.hotspot-users', $data);
    }

    public function active(Request $request) {
        $data = [
            'menu' => 'hotspot',
            'submenu' => 'hotspot-active'
        ];
        return view('pages.hotspot-active', $data);
    }

    public function submitUserProfile(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'sharedusers' => 'integer',
            'price' => 'numeric',
            'sprice' => 'numeric'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => $validator->errors()->first(),
            ];
        }

        // Mikrotik Fields
        $name           = preg_replace('/\s+/', '-', $request->name);
        $ippool         = $request->ippool;
        $sharedusers    = $request->sharedusers;
        $ratelimit      = $request->ratelimit;
        $parentqueue    = $request->parentqueue;

        // Mikuman Fields
        $price          = $request->price;
        $sprice         = $request->sprice;
        $expmode        = $request->expmode;
        $validity       = '1d';
        $ulock          = $request->ulock;
        $slock          = $request->slock;

        $onlogin = !empty($expmode) ? Storage::get('/script/exp') : Storage::get('/script/noexp');
        $recordscript = in_array($expmode, ['remc', 'ntfc']) ? Storage::get('/script/record') : '';
        $ulockscript = $ulock != 'Disable' ? Storage::get('/script/ulock') : '';
        $slockscript = $slock != 'Disable' ? Storage::get('/script/slock') : '';
        $mode = in_array($expmode, ['ntf', 'ntfc']) ? 'N' : 'X';

        if (!empty($recordscript)) {
            $rpls = ['{{name}}','{{price}}','{{validity}}'];
            $recordscript = str_replace($rpls, [$name,$price,$validity], $recordscript);
        }

        $rpls = (!empty($expmode))
            ? ['expmode','mode','price','sprice','validity','ulock','ulockscript','slock','slockscript','recordscript']
            : ['price','sprice','ulock','ulockscript','slock','slockscript'];
            
        foreach($rpls as $key) {
            $onlogin = str_replace('{{'.$key.'}}', ${$key}, $onlogin);
        }

        $response = !empty($request->id)
        ? Mikrotik::request('/ip/hotspot/user/profile/set', [
            ".id" => $request->id,
            "name" => $name,
            "address-pool" => $ippool,
            "rate-limit" => $ratelimit,
            "shared-users" => $sharedusers,
            "status-autorefresh" => "1m",
            "on-login" => trim(preg_replace('/\s+/', ' ', $onlogin)),
            "parent-queue" => $parentqueue,
        ])
        : Mikrotik::request('/ip/hotspot/user/profile/add', [
            "name" => $name,
            "address-pool" => $ippool,
            "rate-limit" => $ratelimit,
            "shared-users" => $sharedusers,
            "status-autorefresh" => "1m",
            "on-login" => trim(preg_replace('/\s+/', ' ', $onlogin)),
            "parent-queue" => $parentqueue,
        ]);

        if(!empty($response['!trap'][0]['message'])) {
            return [
                'success' => false,
                'message' => $response['!trap'][0]['message']
            ];
        }

        return [
            'success' => true,
            'message' => !empty($request->id) 
                ? 'Hotspot user profile has been update.'
                : 'New hotspot user profile has been added.',
        ];
    }

    public function removeUserProfile(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        $response = Mikrotik::request('/ip/hotspot/user/profile/remove', array('.id' => $request->id,));

        return [
            'success' => empty($response),
            'message' => empty($response) ? 'User profile has been remove' : $response['!trap'][0]['message']
        ];
    }
}
