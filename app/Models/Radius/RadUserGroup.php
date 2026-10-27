<?php

namespace App\Models\Radius;

use Illuminate\Database\Eloquent\Model;

class RadUserGroup extends Model
{
    protected $connection = 'radius';
    protected $table = 'radusergroup';
    public $timestamps = false;

    protected $fillable = ['username', 'groupname', 'priority'];
}
