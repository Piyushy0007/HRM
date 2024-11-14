<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ClientPasswordResetRequest;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Client extends Authenticatable implements JWTSubject
{
    use Notifiable;
    //use Notifiable;
    protected $table ="tblm_clients";
    protected $guard = 'clients';

    
    protected $fillable = [
        'firstname', 'lastname','email','police_email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function property(){
        return $this->hasMany('App\Models\ClientProperties', 'client_id', 'id');
    }
   
    public function shift(){
        return $this->hasMany('App\Models\Shift', 'client_property_id', 'id');
    }
    
    public function ShiftEmployee(){
        return $this->belongsTo('App\Models\ShiftEmployee');
    }
    public function employee()
    {
        return $this->hasMany('App\Models\Employee');
    }


    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }


     /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    } 
}
