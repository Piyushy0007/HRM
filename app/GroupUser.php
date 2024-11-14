<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{
    protected $table = 'message_group_user';
    protected $guarded = [ 'id' ];

    Public function group(){
        return $this->belongsTo('App\Group');
    }
    public function message(){
        return $this->belongsTo('App\Message','user_id');
    }

    public function groupable()
    {
        return $this->morphTo(__FUNCTION__, 'groupable_type', 'groupable_id');
    }

}
