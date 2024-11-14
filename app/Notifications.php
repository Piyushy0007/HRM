<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notifications extends Model
{
    use SoftDeletes;
    protected $table= "notifications";
    protected $dates = ['deleted_at'];
    protected $fillable = ['employee_id','subject','description','seen'];

    public function employee()
    {
    	return $this->belongsTo('App\Employee','employee_id');
    }
}
