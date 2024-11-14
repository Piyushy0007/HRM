<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;


class Admin extends Authenticatable
{
    use  Notifiable;

    protected $table ="tblmadminusers";

    

    protected $fillable = [
        'firstname','lastname', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function client()
    {
        return $this->hasMany('App\Models\Client');
    }


    public function clientProperties() {
        return $this->belongsToMany('App\Models\clientProperties');
    }
    
}
