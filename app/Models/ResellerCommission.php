<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResellerCommission extends Model
{
    protected $fillable = [
        'reseller_id',
        'hotspot_user_id',
        'type',
        'amount',
        'notes',
        'created_by',
    ];
    
    public function reseller()
    {
        return $this->belongsTo(User::class, 'reseller_id');
    }

    public function hotspotUser()
    {
        return $this->belongsTo(HotspotUser::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
