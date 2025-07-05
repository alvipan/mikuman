<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Mikrotik;

class PPPoEController extends Controller
{
    public function view($page) {
        $data = [
            'menu' => 'pppoe',
            'submenu' => 'pppoe-'.$page
        ];
        return view('development', $data);
    }

    public function profiles(Request $request) {
        $data = [
            'menu' => 'pppoe',
            'submenu' => 'pppoe-profiles'
        ];
        return view('pages.pppoe.profiles', $data);
    }

    public function users(Request $request) {
        $data = [
            'menu' => 'pppoe',
            'submenu' => 'pppoe-users'
        ];
        return view('pages.pppoe.users', $data);
    }

    public function active(Request $request) {
        $data = [
            'menu' => 'pppoe',
            'submenu' => 'pppoe-active'
        ];
        return view('pages.pppoe.active', $data);
    }

    public function submitProfile(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => $validator->errors()->first(),
            ];
        }

        $name = preg_replace('/\s+/', '-', $request->name);
        $onlyOne = $request['only-one'];
        $rateLimit = $request['rate-limit'];
        $dnsServer = $request['dns-server'];
        $parentQueue = $request['parent-queue'];

        if (!empty($rateLimit) && !$this->rateIsValid($rateLimit)) {
            return [
                'success' => false,
                'message' => 'Invalid rate limit format.'
            ];
        }

        $response = empty($request->id)
            ? Mikrotik::request('/ppp/profile/add', [
                'name' => $name,
                'only-one' => $onlyOne,
                'rate-limit' => $rateLimit,
                'dns-server' => $dnsServer,
                'parent-queue' => $parentQueue
            ])
            : Mikrotik::request('/ppp/profile/set', [
                '.id' => $request->id,
                'name' => $name,
                'only-one' => $onlyOne,
                'rate-limit' => $rateLimit,
                'dns-server' => $dnsServer,
                'parent-queue' => $parentQueue
            ]);

        if (!empty($response['!trap'][0]['message'])) {
            return [
                'success' => false,
                'message' => $response['!trap'][0]['message']
            ];
        }

        return [
            'success' => true,
            'message' => !empty($request->id) 
                ? 'PPPoE profile has been update.'
                : 'PPPoE profile has been added.',
        ];
    }

    public function submitUser(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'local-address' => 'required|ip',
            'remote-address' => 'required|ip'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => $validator->errors()->first(),
            ];
        }

        $data = [
            'name' => preg_replace('/\s+/', '-', $request->name),
            'password' => $request->password,
            'service' => $request->service,
            'profile' => $request->profile,
            'local-address' => $request['local-address'],
            'remote-address' => $request['remote-address']
        ];

        if (!empty($request->id)) {
            $url = '/ppp/secret/set';
            $data['.id'] = $request->id;
        } else {
            $url = '/ppp/secret/add';
        }

        $response = Mikrotik::request($url, $data);

        if (!empty($response['!trap'][0]['message'])) {
            return [
                'success' => false,
                'message' => $response['!trap'][0]['message']
            ];
        }

        return [
            'success' => true,
            'message' => !empty($request->id) 
                ? 'PPPoE user has been update.'
                : 'PPPoE user has been added.',
        ];
    }

    private function rateIsValid($input) {
        return preg_match('/[1-9][kM]\/[1-9][kM]/', $input);
    }

    public function remove(Request $request) {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'data' => 'required'
        ]);

        $cfg = [
            'user' => [
                'url' => '/ppp/secret/remove',
                'msg' => 'The user(s) has been removed.',
            ],
            'profile' => [
                'url' => '/ppp/profile/remove',
                'msg' => 'The profile(s) has been removed.',
            ],
            'active'  => [
                'url' => '/ppp/active/remove',
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
