<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetectShift extends Model
{
    use SoftDeletes;
    protected $table= "detect_shift_duty";
    protected $fillable = ['employee_id','clock_in_time','clock_out_time','onShift','date','shift_id','checked_id'];

    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }

    public function Shift()
   {
       return $this->belongsTo('App\Shift');
   }

   
}
