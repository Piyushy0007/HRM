<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    
	protected $table = 'tblq_message';
    protected $guarded = [ 'id' ];

    public function employee()
    {
        return $this->belongsTo('App\Employee','userable_id');
    }

    public function client()
    {
        return $this->belongsTo('App\Client','userable_id');
    }


    public function messageable()
    {
        return $this->morphTo(__FUNCTION__, 'messageable_type','messageable_id');
    }

    Public function group(){
        return $this->belongsTo('App\Group','group_id');
    }
    public function GroupUser(){
        return $this->belongsTo('App\GroupUser');
    }

   

}
