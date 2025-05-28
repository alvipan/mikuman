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
        $response = Mikrotik::request('/interface/print',['-dynamic'=>'']);
        return $this->json($response);
    }

    public function interfaceTraffic(Request $request) {
        $response = Mikrotik::request('/interface/monitor-traffic', $this->query($request), 'equal');
        return $this->json($response);
    }

    public function hotspotProfiles(Request $request) {
        $router = Router::firstWhere('host', session('router'));
        $response = Mikrotik::request('/ip/hotspot/user/profile/print');
        if (!$response) {
            return $this->json(['data' => []]); 
        }
        
        $profiles = array();
        foreach ($response as $profile) {
            preg_match('/\(\"(.*?)\"\)/', $profile['on-login'], $match);
            $data = explode(',', $match[1],);
            $item = [
                'name' => $profile['name'],
                'rate-limit' => $profile['rate-limit'],
                'shared-users' => $profile['shared-users'].' Users',
                'validity' => $data[3],
                'price' => $router->currency.' '.number_format($data[2], 0),
                'lock-users' => $data[6],
                'lock-server' => $data[7],
            ];
            array_push($profiles, $item);
        }
        return $this->json(['data' => $profiles]);
    }

    public function hotspotUsers(Request $request) {
        $response = Mikrotik::request('/ip/hotspot/user/print', $this->query($request));
        return $this->json($response);
    }

    public function hotspotActive(Request $request) {
        $response = Mikrotik::request('/ip/hotspot/active/print');
        return $this->json($response);
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

    public function income(Request $request) {
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
        return $this->json($response);
    }

    public function logs(Request $request) {
        $result= [];
        $topics = ['pppoe,ppp,info','hotspot,info,debug'];
        $response = Mikrotik::request('/log/print');
        foreach($response as $item) {
            if(in_array($item['topics'], $topics)) {
                array_push($result, $item);
            }
        }
        return $this->json(['data' => array_reverse($result)]);
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
