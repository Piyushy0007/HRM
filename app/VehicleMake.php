<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleMake extends Model
{
    use SoftDeletes;
    protected $table= "tblm_vehicle_make";
    protected $dates = ['deleted_at'];
    protected $fillable = ['vehicle_make_name'];
}
