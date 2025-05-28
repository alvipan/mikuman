<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function view(Request $request) {
        $data = [
            'menu' => 'logs',
            'submenu' => '',
        ];
        return view('pages.logs', $data);
    }
}
