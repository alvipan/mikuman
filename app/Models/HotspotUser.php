<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotspotUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'router_id',
        'profile_id',
        'reseller_id',
        'mikrotik_id',
        'username',
        'password',
        'batch',
        'sale_price',
        'cost_price',
        'used_at',
        'used_ip',
        'used_mac',
        'expired_at',
        'status',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    public function reseller()
    {
        return $this->belongsTo(User::class, 'reseller_id');
    }

    public function profile()
    {
        return $this->belongsTo(HotspotProfile::class, 'profile_id');
    }

    public function getLoginUrlAttribute()
    {
        return sprintf(
            'http://%s/login?username=%s&password=%s',
            $this->router->host,
            $this->code,
            $this->code
        );
    }
}
