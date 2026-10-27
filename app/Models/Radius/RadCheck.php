<?php

namespace App\Models\Radius;

use Illuminate\Database\Eloquent\Model;

class RadCheck extends Model
{
    protected $connection = 'radius';
    protected $table = 'radcheck';
    public $timestamps = false;

    protected $fillable = ['username', 'attribute', 'op', 'value'];
}
