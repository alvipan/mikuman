<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Router;

class ReportController extends Controller
{
    public function view(Request $request) {
        $data = [
            'router' => Router::firstWhere('host', session('router')),
            'menu' => 'report',
            'submenu' => ''
        ];
        return view('pages.report', $data);
    }
}
