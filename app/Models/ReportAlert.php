<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ReportAlert extends Model
{
	use SoftDeletes;
    protected $table = 'tblm_report_alerts';
    protected $fillable = ['employee_id','description','category','data_images'];

    public function employee(){
        return $this->hasOne('App\Employee','id','employee_id');
    }
}