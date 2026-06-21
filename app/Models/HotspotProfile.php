<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterval;

class HotspotProfile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'router_id',
        'mikrotik_id',
        'name',
        'sale_price',
        'cost_price',
        'validity',
        'shared_users',
        'rate_limit',
        'code_length',
        'status'
    ];

    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    public function getExpiredAtAttribute()
    {
        if (! $this->validity) {
            return null;
        }

        return now()->add(
            CarbonInterval::fromString($this->validity)
        );
    }
}
