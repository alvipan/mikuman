<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PppoeSecret extends Model
{
    protected $fillable = [
        'router_id',
        'customer_id',
        'profile_id',
        'mikrotik_id',
        'username',
        'password',
        'service',
        'local_address',
        'remote_address',
        'expired_at',
        'disabled',
    ];

    protected function casts(): array
{
    return [
        'disabled'     => 'boolean',
        'expired_at'   => 'datetime',
    ];
}

    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function profile()
    {
        return $this->belongsTo(PppoeProfile::class);
    }

    public function isExpired(): bool
    {
        return $this->expired_at?->isPast() ?? true;
    }

    public function isActive(): bool
    {
        return ! $this->disabled
            && ! $this->isExpired();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}