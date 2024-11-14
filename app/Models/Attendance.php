<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $table = 'attendances';
    protected $fillable = ['employee_id', 'attendance_date', 'status'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    
}
