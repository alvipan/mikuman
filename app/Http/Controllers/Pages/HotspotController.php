<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Router;

class HotspotController extends Controller
{
    public function profile(Request $request) {
        $data = [
            'router' => Router::firstWhere('host', session('router')),
            'menu' => 'hotspot',
            'submenu' => 'profile'
        ];
        return view('pages.hotspot-profile', $data);
    }
}
