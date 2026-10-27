<?php

namespace App\Services;

use App\Models\Package;
use App\Models\Radius\RadCheck;
use App\Models\Radius\RadUserGroup;
use App\Models\Radius\RadGroupReply;
use Illuminate\Support\Facades\DB;

class RadiusService
{
    /**
     * Sinkronisasi Paket (Profil) ke FreeRADIUS
     */
    public function syncPackage(Package $package)
    {
        $groupName = $package->name; // Kita gunakan nama paket sebagai nama group

        // 1. Bersihkan atribut lama di radgroupreply untuk paket ini
        RadGroupReply::where('groupname', $groupName)->delete();

        // 2. Tambahkan Rate Limit (Kecepatan)
        if ($package->rate_limit) {
            RadGroupReply::create([
                'groupname' => $groupName,
                'attribute' => 'Mikrotik-Rate-Limit',
                'op'        => ':=',
                'value'     => $package->rate_limit,
            ]);
        }

        // 3. Tambahkan Simultaneous-Use (Batas Login Bersamaan)
        if ($package->shared_users > 0) {
            RadGroupReply::create([
                'groupname' => $groupName,
                'attribute' => 'Simultaneous-Use',
                'op'        => ':=',
                'value'     => $package->shared_users,
            ]);
        }

        // 4. (Opsional) Tambahkan Session-Timeout (Masa Aktif dalam detik)
        // Jika tipe paket PPPoE, masa aktif biasanya dikelola via billing_date.
        // Tapi untuk Hotspot/Voucher, kita bisa set timeout langsung.
        if ($package->type === 'hotspot') {
            $validityInSeconds = match ($package->validity_unit) {
                'hour'  => $package->validity_value * 3600,
                'day'   => $package->validity_value * 86400,
                'month' => $package->validity_value * 2592000, // Asumsi 30 hari
                default => 0,
            };

            if ($validityInSeconds > 0) {
                RadGroupReply::create([
                    'groupname' => $groupName,
                    'attribute' => 'Session-Timeout',
                    'op'        => ':=',
                    'value'     => $validityInSeconds,
                ]);
            }
        }
    }

    /**
     * Menambahkan Voucher atau PPPoE Secret ke FreeRADIUS
     */
    public function syncUser(string $username, string $password, ?string $groupName = null)
    {
        // 1. Simpan Username & Password (Cleartext)
        // Hapus dulu jika sudah ada (untuk update password)
        RadCheck::where('username', $username)
            ->where('attribute', 'Cleartext-Password')
            ->delete();

        RadCheck::create([
            'username'  => $username,
            'attribute' => 'Cleartext-Password',
            'op'        => ':=',
            'value'     => $password,
        ]);

        // 2. Hubungkan ke Group (Paket)
        if ($groupName) {
            RadUserGroup::where('username', $username)->delete();
            RadUserGroup::create([
                'username'  => $username,
                'groupname' => $groupName,
                'priority'  => 1,
            ]);
        }
    }

    /**
     * Menghapus User dari FreeRADIUS (Misal: voucher dihapus atau diisolir)
     */
    public function deleteUser(string $username)
    {
        RadCheck::where('username', $username)->delete();
        RadUserGroup::where('username', $username)->delete();
    }

    /**
     * Isolir / Disable User (Ganti password jadi password acak agar tidak bisa login)
     */
    public function isolateUser(string $username)
    {
        RadCheck::where('username', $username)
            ->where('attribute', 'Cleartext-Password')
            ->update(['value' => 'ISOLIR_' . uniqid()]);
    }
}
