<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetectShift extends Model
{
    //
    protected $table= "detect_shift_duty";

    protected $fillable = ['employee_id','clock_in_time','clock_out_time','onShift','date','shift_id','checked_id','reason'];

    // public function DetectShift()
    // {
    //   return $this->belongsTo('App\Models\DetectShift');
    // }

    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }

    public function Shift()
    {
       return $this->belongsTo('App\Shift');
    }
    
}
