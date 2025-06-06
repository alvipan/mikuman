<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\Router;
use App\Helpers\Mikrotik;
use App\Http\Controllers\GETController;
use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\HotspotController;
use App\Http\Controllers\Pages\ReportController;
use App\Http\Controllers\Pages\LogController;
use App\Http\Controllers\Pages\RouterController;

Route::match(['post','get'],'/', function (Request $request) 
{
    if ($request->isMethod('get')) {
        $data = [
            'routers' => Router::get(),
        ];
        return view('login', $data);
    }

    $validator = Validator::make($request->all(), [
        'host' => 'required|ip',
        'user' => 'required'
    ]);

    if ($validator->fails()) {
        return [
            'success' => false,
            'message' => $validator->errors()->first(),
        ];
    }

    $router = Router::firstOrNew(['host' => $request->host]);
    $router->user = $request->user;
    $router->pass = $request->pass;

    $connected = Mikrotik::connect($router);

    if ($connected) {
        session(['router' => $router->host]);
        $router->save();
        return [
            'success' => true,
            'message' => 'Connected successfull.'
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Cannot connected to the router.'
        ];
    }
})->middleware('disconnected')->name('login');

Route::get('/test', function() {
    $content = Storage::get('/script/exp.txt');
    return $content;
});

Route::get('/logout', function(Request $request) 
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
});

Route::middleware('connected')->controller(GETController::class)->group(function() {
    Route::get('/get/test', 'test');

    Route::get('/system/resources', 'systemResources');
    Route::get('/system/health', 'systemHealth');

    Route::get('/get/interface', 'interfaces');
    Route::get('/get/interface/traffic', 'interfaceTraffic');

    Route::get('/get/ip/pool', 'IPPool');
    Route::get('/get/queue', 'queue');

    Route::get('/get/hotspot/profiles', 'hotspotProfiles');
    Route::get('/get/hotspot/users', 'hotspotUsers');
    Route::get('/get/hotspot/active', 'hotspotActive');

    Route::get('/ppp/profiles', 'pppProfiles');
    Route::get('/ppp/users', 'pppUsers');
    Route::get('/ppp/active', 'pppActive');

    Route::get('/get/report', 'report');

    Route::get('/get/logs', 'logs');
});

Route::controller(RouterController::class)->group(function() {
    Route::post('/router/connect', 'connect');
    Route::post('/router/delete', 'delete');
});

Route::middleware('connected')->controller(DashboardController::class)->group(function() {
    Route::get('/dashboard', 'view')->name('dashboard');
});

Route::middleware('connected')->controller(HotspotController::class)->group(function() {
    Route::get('/hotspot/profiles', 'profiles')->name('hotspot-profiles');
    Route::get('/hotspot/users', 'users')->name('hotspot-users');
    Route::get('/hotspot/active', 'active')->name('hotspot-active');
    Route::post('/hotspot/profiles/submit', 'submitUserProfile');
    Route::post('/hotspot/profiles/remove', 'removeUserProfile');
});

Route::middleware('connected')->controller(ReportController::class)->group(function() {
    Route::get('/report', 'view')->name('report');
});

Route::middleware('connected')->controller(LogController::class)->group(function() {
    Route::get('/logs', 'view')->name('logs');
});
