<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Violation extends Model
{
    use SoftDeletes;
    protected $table= "tblm_violation";

    protected $fillable = [
        'employee_id', 'property_id','checked_id', 'shift_id','start_time','end_time', 'violation_date','violation_status','breakaction','travelaction'
    ];
}
