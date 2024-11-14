<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeParking extends Model
{
	use SoftDeletes;
    protected $table= "tblm_parking";
    protected $dates = ['deleted_at'];
    protected $fillable = ['employee_id','client_id','property_id','parking_location',
    'vehicle_type','vehicle_make','vehicle_model','status','licence_plate','color','description','vehicle_image'];

    public function vechiclemake(){
        return $this->hasMany('App\VehicleMake','id','vehicle_make');
    }
    public function vechiclemodel(){
        return $this->hasMany('App\VehicleModel','id','vehicle_model');
    }
    public function vechicletype(){
        return $this->hasMany('App\VehicleType','id','vehicle_type');
    }
    public function property(){
        return $this->hasMany('App\ClientProperties','id','property_id');
    }
    public function client(){
        return $this->hasMany('App\Client','id','client_id');
    }
}
