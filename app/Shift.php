<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Shift extends Model
{
    use SoftDeletes,Notifiable;
    
	protected $table = 'tblm_shifts';
    protected $guarded = [ 'id' ];

    /**
     * Get the position
     */
    public function position()
    {
    	return $this->belongsTo('App\Position');
    }

    public function employee()
    {
    	return $this->belongsTo('App\Employee','employee_id');
    }

    public function property() 
    {
        return $this->belongsTo('App\ClientProperties', 'client_property_id');
    }


    public function clockIn() 
    {
        return $this->hasMany('App\Clockin', 'shift_id','id');
    }

    public function logs(){
        return $this->hasMany('App\DetectShift', 'shift_id');
    }

    
    
}
