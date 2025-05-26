<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Mikrotik;

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
        $response = Mikrotik::request('/ip/hotspot/profile/print');
        return $this->json($response);
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
        $response = Mikrotik::request('/log/print', $this->query($request));
        return $this->json($response);
    }

    public function query(Request $request) {
        $conditions = [];
        foreach($request->query() as $key => $val) {
            $conditions[$key] = $val;
        }
        return $conditions;
    }

    public function json($input) {
        return response()->json($input, 200, [], JSON_PRETTY_PRINT);
    }
}
