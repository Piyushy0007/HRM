<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftEmployee extends Model
{


    protected $table = 'tblmq_shift_employee';
    protected $guarded = [ 'id' ];

    /**
     * Get the employee
     */
    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }

    /**
     * Get the shift
     */
    public function shift()
    {
        return $this->belongsTo('App\Models\Shift');
    }

    public function shiftDate()
    {
        return $this->hasMany('App\Models\ShiftDate','shift_id','shift_id');
    }
    
    public function timeoff(){
        return $this->hasMany('App\Timeoff','employee_id','employee_id');

    }

    public function clockin(){
        return $this->hasMany('App\Clockin','shift_id','shift_id');
    }
   

    
}
