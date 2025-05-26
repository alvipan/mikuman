<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function view() {
        $data = [
            'menu' => 'dashboard',
        ];
        return view('pages.dashboard', $data);
    }
}
