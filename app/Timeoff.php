<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timeoff extends Model
{
    protected $table = 'time_off_request';
    protected $guarded = [ 'id' ];


    public function employee()
    {
    	return $this->belongsTo('App\Employee');
    }
}
