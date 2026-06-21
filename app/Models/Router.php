<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Router extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'host',
        'port',
        'username',
        'password',
    ];

    public function resellers()
    {
        return $this->hasMany(User::class);
    }

    public function pppoeProfiles()
    {
        return $this->hasMany(PppoeProfile::class);
    }

    public function pppoeSecrets()
    {
        return $this->hasMany(PppoeSecret::class);
    }
}
