<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeePosition extends Model
{
    //
    protected $table = 'tblmq_employee_positions';

    protected $fillable = ['employee_id','position_id'];
}
