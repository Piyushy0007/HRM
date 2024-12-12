<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Employee extends Authenticatable implements JWTSubject
{
	 use Notifiable,SoftDeletes;

	protected $table = 'tblm_employee';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard  = 'employee';
    protected $fillable = ['firstname', 'lastname','email','phone','password',
         'remember_token', 'created_at','updated_at','zip',
        'deleted_at','deviceToken','background_check','background_data','plain_password'];
   
  


   

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function shifts() {
        return $this->belongsToMany('App\Models\Shift', 'tblm_shifts');
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
    

    public function timeoff(){
        return $this->hasMany('App\Timeoff');

    }

    public function clockin(){
        return $this->hasMany('App\Clockin');
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class); // Assuming Experience is the related model
    }

    public function clientRequests()
{
    return $this->hasMany(ClientRequest::class, 'employee_id');
}

public function jobs()
{
    return $this->hasMany('App\Models\Job');
}





    // public function salarySlips()
    // {
    //     return $this->hasMany('App\SalarySlips');
    // }

    


}
