<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'pppoe_secret_id',
        'amount',
        'months',
        'note',
        'paid_at',
    ];

    public function pppoeSecret()
    {
        return $this->belongsTo(PppoeSecret::class);
    }
}
