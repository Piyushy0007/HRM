<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleModel extends Model
{
    use SoftDeletes;
    protected $table= "tblm_vehicle_model";
    protected $dates = ['deleted_at'];
    protected $fillable = ['vehicle_model_name'];
}
