<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PppoeProfile extends Model
{
    protected $fillable = [
        'router_id',
        'mikrotik_id',
        'name',
        'price',
        'rate_limit',
        'local_address',
        'remote_address',
        'only_one',
        'change_tcp_mss',
    ];

    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    public function secrets()
    {
        return $this->hasMany(PppoeSecret::class, 'profile_id');
    }
}