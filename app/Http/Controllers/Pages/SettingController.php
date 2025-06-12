<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Router;

class SettingController extends Controller
{
    public function view(Request $request) {
        $data = [
            'router' => Router::firstWhere('host', session('router')),
            'menu' => 'settings',
            'submenu' => ''
        ];
        return view('pages.settings', $data);
    }
}
