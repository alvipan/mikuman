<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable; // Bisa login

class Customer extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'is_active'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function secrets(): HasMany
    {
        return $this->hasMany(Secret::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
