<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    //
    protected $table = 'tblm_shifts';
    protected $guarded = [ 'id' ];

    /**
     * Get the position
     */
    public function position()
    {
    	return $this->belongsTo('App\Models\Position');
    }

    public function singleEmployee(){
        return $this->belongsToMany('App\Models\Employee', 'tblmq_shift_employee');
    }

    public function employee()
    {
    	return $this->belongsTo('App\Models\Employee', 'employee_id');
    }

    public function property() {
        return $this->belongsTo('App\Models\ClientProperties', 'client_property_id');
    }

    // public function parking() {
    //     return $this->belongsToMany('App\Models\EmployeeParking', 'employee_id');
    // }

    public function logs(){
        return $this->hasMany('App\Models\DetectShift', 'shift_id');
    }

   
}
