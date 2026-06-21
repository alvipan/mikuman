<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\PppoeSecret;
use App\Models\PppoeProfile;
use App\Models\Router;
use App\Services\Pppoe\PppoeSecretService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerService
{
    public function create(array $data): Customer
    {
        return DB::transaction(function () use ($data) {

            // 1. Create customer
            $customer = Customer::create([
                'name' => $data['name'],
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
            ]);

            return $customer;
        });
    }

    protected function generateUsername(Customer $customer): string
    {
        return 'CUST' . str_pad($customer->id, 5, '0', STR_PAD_LEFT);
    }

    protected function generatePassword(int $length = 6): string
    {
        return Str::random($length);
    }
}