<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\Router;
use App\Http\Controllers\GETController;
use App\Http\Controllers\POSTController;
use App\Http\Controllers\Pages\HotspotController;

use App\Livewire\Login;
use App\Livewire\Dashboard;
use App\Livewire\Logs;
use App\Livewire\Report;
use App\Livewire\About;

use App\Livewire\Hotspot\HotspotProfiles;
use App\Livewire\Hotspot\HotspotUsers;
use App\Livewire\Hotspot\HotspotActive;

use App\Livewire\Pppoe\PppoeProfiles;
use App\Livewire\Pppoe\PppoeSecrets;
use App\Livewire\Pppoe\PppoeActive;

Route::get('/', Login::class)->middleware('disconnected')->name('login');

Route::get('/logout', function(Request $request) 
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::middleware('connected')->controller(GETController::class)->group(function() {
    Route::get('/get/expire-monitor', 'expireMonitor');
});

Route::middleware('connected')->controller(POSTController::class)->group(function() {
    Route::post('/post/expire-monitor', 'expireMonitor');
});

Route::middleware('connected')->group(function() {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::get('/hotspot/profiles', HotspotProfiles::class)->name('hotspot.profiles');
    Route::get('/hotspot/users', HotspotUsers::class)->name('hotspot.users');
    Route::get('/hotspot/active', HotspotActive::class)->name('hotspot.active');

    Route::get('/pppoe/profiles', PppoeProfiles::class)->name('pppoe.profiles');
    Route::get('/pppoe/secrets', PppoeSecrets::class)->name('pppoe.secrets');
    Route::get('/pppoe/active', PppoeActive::class)->name('pppoe.active');

    Route::get('/report', Report::class)->name('report');
    Route::get('/logs', Logs::class)->name('logs');
    Route::get('/about', About::class)->name('about');
});

Route::middleware('connected')->controller(HotspotController::class)->group(function() {
    Route::get('/hotspot/users/print', 'print');
});