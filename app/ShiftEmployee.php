<?php

namespace App;

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
    	return $this->belongsTo('App\Employee');
    }

    /**
     * Get the shift
     */
    public function shift()
    {
        return $this->belongsToMany('App\Shift');
    }
}
