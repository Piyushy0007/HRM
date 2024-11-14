<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Reportslog extends Model
{
	use SoftDeletes;
    protected $table = 'tblm_report_logs';
    protected $fillable = ['employee_id','property_id','report_type_id','location','description','date','status'];

    public function employee(){
        return $this->hasOne('App\Employee','id','employee_id');
    }

    public function client(){
        return $this->hasOne('App\Client','id','client_id');
    }

    public function property(){
        return $this->hasOne('App\ClientProperties','id','property_id');
    }

    public function report(){
        return $this->hasOne('App\ReportType','id','report_type_id');
    }
    public function subReport(){
        return $this->hasOne('App\ReportSubType','id','report_subtype_id');
    }
}
