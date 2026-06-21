<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
        'status',
    ];

    public function secrets()
    {
        return $this->hasMany(PppoeSecret::class);
    }
}
