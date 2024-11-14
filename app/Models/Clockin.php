<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clockin extends Model
{
    protected $table = 'tblm_employee_checking';
    protected $fillable = ['employee_id','start_time','end_time','break_time','location','latitude','longitude','shiftaction','shift_id'];

    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }

    public function Shift()
    {
        return $this->belongsTo('App\Shift');
    }
    public function detectShift()
    {
        return $this->hasMany('App\DetectShift','checked_id','id');
    }
        
}
