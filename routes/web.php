<?php

use Illuminate\Support\Facades\Route;

// Redirect halaman awal ke dashboard (atau login jika belum auth)
Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware('guest')->group(function () {
    Route::livewire('/login', 'pages::auth.login')->name('login');
});

// Rute Auth (Hanya bisa diakses setelah login)
Route::middleware('auth')->group(function () {
    Route::livewire('/dashboard', 'pages::dashboard')->name('dashboard');
    Route::livewire('/routers', 'pages::routers.index')->name('routers.index');

    // Rute Hotspot
    // Route::livewire('/hotspot/packages', 'pages::hotspots.packages')->name('hotspot.packages');
    Route::livewire('/hotspot/vouchers', 'pages::hotspots.vouchers')->name('hotspot.vouchers');
    // Route::livewire('/hotspot/active', 'pages::hotspots.active')->name('hotspot.active');

    // Route logout bawaan
    Route::post('/logout', function () {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});
