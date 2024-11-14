<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleType extends Model
{
    use SoftDeletes;
    protected $table= "tblm_vehicle_type";
    protected $dates = ['deleted_at'];
    protected $fillable = ['vehicle_type_name'];
}
