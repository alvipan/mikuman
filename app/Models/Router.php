<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Router extends Model
{
    protected $fillable = [
        'name',
        'address',
        'protocol',
        'port',
        'username',
        'password',
        'is_active',
        'lan_ip',
        'lan_interface',
        'wlan_ssid',
        'hotspot_subnet',
        'pppoe_subnet'
    ];

    protected $casts = [
        'password' => 'encrypted',
        'is_active' => 'boolean',
    ];

    /**
     * Mendapatkan Base URL untuk REST API RouterOS
     */
    public function getBaseUrlAttribute(): string
    {
        return "{$this->protocol}://{$this->address}:{$this->port}/rest";
    }

    public function secrets(): HasMany
    {
        return $this->hasMany(Secret::class);
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }
}
