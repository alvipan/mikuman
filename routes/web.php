<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (User::count() === 0) {
        return redirect()->route('setup');
    }
    return redirect()->route('login');
});

Route::get('/logout', function () {
    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');

Route::livewire('/setup', 'pages::setup.index')->middleware('not_installed')->name('setup');

Route::livewire('/login', 'pages::auth.login')->middleware(['installed','guest'])->name('login');

Route::middleware('auth')
    ->prefix('vouchers')
    ->name('vouchers.')
    ->group(function () {

        Route::livewire('/sell', 'pages::vouchers.sell')->name('sell');
    });

Route::middleware(['auth', 'admin'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {

        // router list
        Route::livewire('/', 'pages::dashboard.index')
            ->name('index');
    });

Route::middleware(['auth', 'admin'])
    ->prefix('routers')
    ->name('routers.')
    ->group(function () {

        // router list
        Route::livewire('/', 'pages::routers.index')
            ->name('index');

        // router context
        Route::middleware('connected')->group(function () {

            Route::livewire('/dashboard', 'pages::routers.dashboard')
                ->name('dashboard');

        });
    });

Route::middleware(['auth'])
    ->prefix('resellers')
    ->name('resellers.')
    ->group(function () {

        Route::livewire('/app', 'pages::resellers.app.index')->name('app.index');
        
        Route::middleware('admin')->group(function (){
            Route::livewire('/', 'pages::resellers.index')->name('index');
            Route::livewire('/{reseller}', 'pages::resellers.detail')->name('detail');
        });
    });

Route::middleware(['auth', 'admin'])
    ->prefix('hotspot')
    ->name('hotspot.')
    ->group(function () {

        Route::livewire('/', 'pages::hotspot.index')->name('index');
    });

Route::middleware(['auth', 'admin'])
    ->prefix('pppoe')
    ->name('pppoe.')
    ->group(function () {

        Route::livewire('/', 'pages::pppoe.index')->name('index');
        Route::livewire('/customer/{customer}', 'pages::pppoe.customer')->name('customer');
    });

Route::middleware(['auth', 'admin'])
    ->prefix('reports')
    ->name('reports.')
    ->group(function () {

        Route::livewire('/', 'pages::reports.index')->name('index');
    });

Route::get('/print/vouchers', function () {

    $batch = request('batch');
    $color = request('color', 'blue');
    $showQr = request('qr', 0);

    $vouchers = \App\Models\HotspotUser::with(['profile', 'router'])
        ->where('batch', $batch)
        ->get();

    return view('print.vouchers', compact(
        'vouchers',
        'color',
        'batch',
        'showQr'
    ));
})->name('vouchers.print');

Route::middleware(['auth', 'admin'])
    ->prefix('settings')
    ->name('settings.')
    ->group(function () {

        Route::livewire('/', 'pages::settings.index')->name('index');
    });
