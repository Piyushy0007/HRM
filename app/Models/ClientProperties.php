<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientProperties extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table ="tblmq_client_properties";

    public function client(){
        return $this->belongsTo('App\Models\Client', 'client_id');
    }
    public function shift(){
        return $this->hasMany('App\Models\Shift','client_property_id');
    }
    
    public function ShiftEmployee(){
        return $this->belongsTo('App\Models\ShiftEmployee', 'shift_id', 'id');
    }
    public function employee()
    {
        return $this->belongTo('App\Models\Employee','id', 'employee_id');
    }

    

    
}