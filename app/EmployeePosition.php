<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeePosition extends Model
{
	protected $table = 'tblmq_employee_positions';
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
    public function position()
    {
    	return $this->belongsTo('App\Position');
    }
}
