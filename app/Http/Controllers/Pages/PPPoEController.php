<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PPPoEController extends Controller
{
    public function view($page) {
        $data = [
            'menu' => 'pppoe',
            'submenu' => 'pppoe-'.$page
        ];
        return view('development', $data);
    }

    public function profiles(Request $request) {
        $data = [
            'menu' => 'pppoe',
            'submenu' => 'profiles'
        ];
        return view('pages.pppoe-profiles', $data);
    }

    public function users(Request $request) {
        $data = [
            'menu' => 'pppoe',
            'submenu' => 'users'
        ];
        return view('pages.pppoe-users', $data);
    }

    public function active(Request $request) {
        $data = [
            'menu' => 'pppoe',
            'submenu' => 'active'
        ];
        return view('pages.pppoe-active', $data);
    }
}
