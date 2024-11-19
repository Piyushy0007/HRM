<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $table = 'attendances';
    protected $fillable = ['employee_id', 'attendance_date', 'status'];

    // Constants for Attendance Status
    const STATUS_PRESENT = 1;
    const STATUS_ABSENT = 0;

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }

    
}
