<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function view(Request $request) {
        $data = [
            'menu' => 'report',
            'submenu' => ''
        ];
        return view('pages.report', $data);
    }
}
