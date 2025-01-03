<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $table = 'leave_requests';
    protected $fillable = [
        'employee_id', 
        'email', 
        'leave_type', 
        'start_date', 
        'end_date', 
        'reason', 
        'status', 
        'leave_date'
    ];

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;

    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }
}
