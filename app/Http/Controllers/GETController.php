<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Mikrotik;
use App\Models\Router;

class GETController extends Controller
{
    public function test(Request $request) {
        $response = Mikrotik::request('/sys/clock/print');
        return $this->json($response);
    }

    public function systemResources(Request $request) {
        $response = Mikrotik::request('/sys/resource/print');
        return $this->json($response);
    }

    public function systemHealth(Request $request) {
        $response = Mikrotik::request('/sys/health/print');
        return $this->json($response);
    }

    public function interfaces(Request $request) {
        $arr = array();
        $response = Mikrotik::request('/interface/print');
        foreach($response as $item) {
            if (!isset($item['dynamic'])) {
                array_push($arr, $item);
            }
        }
        return $this->json($arr);
    }

    public function IPPool(Request $request) {
        $response = Mikrotik::request('/ip/pool/print');
        return $this->json($response);
    }

    public function queue(Request $request) {
        $response = Mikrotik::request("/queue/simple/print", array("?dynamic" => "false",));
        return $this->json($response);
    }

    public function interfaceTraffic(Request $request) {
        $router = Router::firstWhere('host', session('router'));
        if (!empty($router->interface)) {
            $response = Mikrotik::request('/interface/monitor-traffic', ['interface' => $router->interface, 'once' => ''], 'equal');
            return [
                'success' => true,
                'data' => $this->json($response[0])
            ];
        }
        return [
            'success' => false
        ];
    }

    public function hotspotProfiles(Request $request) {
        $router = Router::firstWhere('host', session('router'));
        $response = Mikrotik::request('/ip/hotspot/user/profile/print');
        if (!$response) {
            return $this->json(['data' => []]); 
        }
        
        $profiles = array();
        foreach ($response as $profile) {
            if (isset($profile['on-login'])) {
                preg_match('/\(\"(.*?)\"\)/', $profile['on-login'], $match);
                $data = explode(',', $match[1],);
            }
            
            $item = [
                'id'            => $profile['.id'],
                'name'          => $profile['name'],
                'ip-pool'       => isset($profile['address-pool']) ? $profile['address-pool'] : 'none' ,
                'rate-limit'    => isset($profile['rate-limit']) ? $profile['rate-limit'] : '-',
                'shared-users'  => $profile['shared-users'],
                'parent-queue'  => $profile['parent-queue'],
                'expire-mode'   => isset($data[1]) ? $data[1] : 'none',
                'price'         => isset($data[2]) ? $data[2] : '0',
                'validity'      => isset($data[3]) ? $data[3] : '-',
                'sprice'        => isset($data[4]) ? $data[4] : '0',
                'lock-users'    => isset($data[6]) ? $data[6] : 'Disable',
                'lock-server'   => isset($data[7]) ? $data[7] : 'Disable',
            ];
            array_push($profiles, $item);
        }
        return $this->json(['data' => $profiles]);
    }

    public function hotspotUsers(Request $request) {
        $response = Mikrotik::request('/ip/hotspot/user/print');
        foreach ($response as $i => $v) {
            $response[$i]['id'] = $response[$i]['.id'];
            unset($response[$i]['.id']);
        }
        return $this->json(['data' => $response]);
    }

    public function hotspotActive(Request $request) {
        $response = Mikrotik::request('/ip/hotspot/active/print');
        foreach ($response as $i => $v) {
            $response[$i]['id'] = $response[$i]['.id'];
            unset($response[$i]['.id']);
        }
        return $this->json(['data' => $response]);
    }

    public function pppProfiles(Request $request) {
        $response = Mikrotik::request('/ppp/profile/print');
        return $this->json($response);
    }

    public function pppUsers(Request $request) {
        $response = Mikrotik::request('/ppp/secret/print', $this->query($request));
        return $this->json($response);
    }

    public function pppActive(Request $request) {
        $response = Mikrotik::request('/ppp/active/print');
        return $this->json($response);
    }

    public function report(Request $request) {
        $response = Mikrotik::request('/sys/script/print');
        if ($request->has('summary')) {
            $data = [
                'today' => 0,
                'yesterday' => 0,
                'this-month' => 0,
                'last-month' => 0
            ];

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
        $result = array();
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
            array_push($result, $item);
        }
        return $this->json(['data' => array_reverse($result)]);
    }

    public function logs(Request $request) {
        $arr = array();
        $response = Mikrotik::request('/log/print', ['?topics' => 'hotspot,info,debug']);
        foreach ($response as $item) {
            if (str_contains($item['message'], '->')) {
                array_push($arr, $item);
            }
        }

        return $this->json(['data' => array_reverse($arr)]);
    }

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

    public function query(Request $request) {
        $conditions = [];
        foreach($request->query() as $key => $val) {
            '?'.$conditions[$key] = $val;
        }
        return $conditions;
    }

    public function json($input) {
        return response()->json($input, 200, [], JSON_PRETTY_PRINT);
    }
}
