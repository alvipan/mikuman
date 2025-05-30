<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Router;

class DashboardController extends Controller
{
    public function view() {
        $data = [
            'router' => Router::firstWhere('host', session('router')),
            'menu' => 'dashboard',
            'submenu' => '',
        ];
        return view('pages.dashboard', $data);
    }
}
