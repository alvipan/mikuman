<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

use App\Models\User;

use App\Http\Controllers\Admin\RouterController;

Route::match(['post','get'],'/', function (Request $request) {

    $user = User::firstOrCreate(
        ['id' => '1'],
        ['username' => 'mikuman', 'password' => Hash::make('mikuman')]
    );

    if ($request->isMethod('get')) {
        return view('login');
    }

    $credentials = $request->validate([
        'username' => ['required'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect('/routers');
    }

    return back()->withErrors([
        'username' => 'The provided credentials do not match.',
    ]);
})->middleware('guest')->name('login');

Route::get('/logout', function(Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
});

Route::middleware('auth')->controller(RouterController::class)->group(function() {
    Route::get('/routers', 'view')->name('dashboard');
});
