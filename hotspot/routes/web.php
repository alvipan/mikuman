<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

use App\Livewire\Shop;
use App\Livewire\Checkout;
use App\Livewire\Test;

Route::get('/', Shop::class);
Route::get('/checkout/{voucher}', Checkout::class);
Route::get('/test', Test::class);
