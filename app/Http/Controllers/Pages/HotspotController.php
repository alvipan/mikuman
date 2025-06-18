<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\file;
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
        $validity       = $request->validity;
        $ulock          = $request->ulock;
        $slock          = $request->slock;

        $onlogin = !empty($expmode) 
            ? File::get(public_path('/assets/scripts/exp'))
            : File::get(public_path('/assets/scripts/noexp'));
        $recordscript = in_array($expmode, ['remc', 'ntfc']) ? File::get(public_path('/assets/scripts/record')) : '';
        $ulockscript = $ulock != 'Disable' ? File::get(public_path('/assets/scripts/ulock')) : '';
        $slockscript = $slock != 'Disable' ? File::get(public_path('/assets/scripts/slock')) : '';
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

        if(!empty($response['!trap'])) {
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

    public function users(Request $request) {
        $data = [
            'menu' => 'hotspot',
            'submenu' => 'hotspot-users'
        ];
        return view('pages.hotspot-users', $data);
    }

    public function generateUsers(Request $request) {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => $validator->errors()->first(),
            ];
        }

        // Get fields submitted
        $quantity       = $request->quantity;
        $server         = $request->get('server');
        $credential     = $request->credential;
        $length         = $request->length;
        $profile        = $request->profile;
        $comment        = !empty($request->comment) 
            ? 'vc-'.substr(mt_rand(), 0, 6).'-'.$request->comment 
            : 'vc-'.substr(mt_rand(), 0, 6);

        for ($i = 0; $i < $quantity; $i++) {
            $name = $this->randString($length);
            $pass = $credential == 'unp' 
                ? $this->randString($length)
                : $name;

            $user = array(
                'server'    => $server,
                'name'      => strtoupper($name),
                'password'  => strtoupper($pass),
                'profile'   => $profile,
                'comment'   => $comment
            );

            $response = Mikrotik::request('/ip/hotspot/user/add', $user);
            if (!empty($response['!trap'][0]['message'])) {
                return [
                    'success' => false,
                    'message' => $response['!trap'][0]['message']
                ];
            }
        }

        return [
            'success' => true,
            'message' => 'Hotspot users has been generate'
        ];
    }

    public function editUser(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => $validator->errors()->first(),
            ];
        }

        $response = Mikrotik::request('/ip/hotspot/user/set', [
            '.id'       => $request->id,
            'server'    => !empty($request->get('server')) ? $request->get('server') : 'all',
            'name'      => $request->name,
            'password'  => $request->password,
            'profile'   => $request->profile,
            'comment'   => $request->comment
        ]);

        if (!empty($response['!trap'][0]['message'])) {
            return [
                'success' => false,
                'message' => $response['!trap'][0]['message']
            ];
        }

        return [
            'success' => true,
            'message' => 'Hotspot users has been updated'
        ];
    }

    public function print(Request $request) {
        $validator = Validator::make($request->all(), [
            'comment' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => true,
                'message' => $validator->errors()->first()
            ];
        }

        $users = Mikrotik::request('/ip/hotspot/user/print', ['?comment' => $request->comment]);
        $profile = Mikrotik::request('/ip/hotspot/user/profile/print', ['?name' => $users[0]['profile']]);

        $data = [
            'users'     => $users,
            'profile'   => $profile,
            'color'     => $request->get('color', 'red'),
            'qrcode'    => $request->get('qrcode', null),
            'router'    => Router::firstWhere('host', session('router'))
        ];
        return view('pages.hotspot-print', $data);
    }

    private function randString($length) {
        $char = '2346789ABCDEFGHJKLMNPQRTUVWXYZ';
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $char[rand(0, strlen($char) -1)];
        }
        return $result;
    }

    public function remove(Request $request) {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'data' => 'required'
        ]);

        $cfg = [
            'user' => [
                'url' => '/ip/hotspot/user/remove',
                'msg' => 'The user(s) has been removed.',
            ],
            'profile' => [
                'url' => '/ip/hotspot/user/profile/remove',
                'msg' => 'The user profile(s) has been removed.',
            ],
            'active'  => [
                'url' => '/ip/hotspot/user/active/remove',
                'msg' => 'The active user(s) has been removed',
            ],
        ];

        if (
            $validator->fails() || 
            !isset($cfg[$request->type]) || 
            count($request->data) <= 0
        ) {
            return [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        foreach($request->data as $id) {
            $response = Mikrotik::request($cfg[$request->type]['url'], ['.id' => $id]);
            if (isset($response['!trap'])) {
                return [
                    'success' => false,
                    'message' => $response['!trap'][0]['message']
                ];
            }
        }

        return [
            'success' => true,
            'message' => $cfg[$request->type]['msg']
        ];
    }
}
