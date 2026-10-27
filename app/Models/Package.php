<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'name',
        'type',
        'rate_limit',
        'validity_value',
        'validity_unit',
        'shared_users',
        'sale_price',
        'cost_price',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
    ];

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }

    public function secrets(): HasMany
    {
        return $this->hasMany(Secret::class);
    }
}
