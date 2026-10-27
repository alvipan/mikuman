<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'app_name', 'value' => 'Mikuman', 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_name', 'value' => 'Via WiFi', 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_address', 'value' => 'Alamat Perusahaan', 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_phone', 'value' => '08123456789', 'type' => 'string', 'group' => 'general'],
            ['key' => 'timezone', 'value' => 'Asia/Jakarta', 'type' => 'string', 'group' => 'general'],
            ['key' => 'currency', 'value' => 'IDR', 'type' => 'string', 'group' => 'general'], // <-- Tambahan
            ['key' => 'currency_symbol', 'value' => 'Rp', 'type' => 'string', 'group' => 'general'], // <-- Tambahan

            // Billing
            ['key' => 'invoice_prefix', 'value' => 'INV-', 'type' => 'string', 'group' => 'billing'],
            ['key' => 'billing_due_days', 'value' => '7', 'type' => 'integer', 'group' => 'billing'],
            ['key' => 'tax_percentage', 'value' => '0', 'type' => 'string', 'group' => 'billing'],

            // Radius
            ['key' => 'radius_server_ip', 'value' => '10.30.0.10', 'type' => 'string', 'group' => 'radius'],
            ['key' => 'radius_default_secret', 'value' => 'rahasia_radius_123', 'type' => 'string', 'group' => 'radius'],
            ['key' => 'radius_default_shared_users', 'value' => '1', 'type' => 'integer', 'group' => 'radius'],

            // Reseller
            ['key' => 'reseller_commission_type', 'value' => 'margin', 'type' => 'string', 'group' => 'reseller'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
