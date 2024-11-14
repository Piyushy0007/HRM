<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //
    protected $table = 'message_group';
    protected $guarded = [ 'id' ];
    /**
     * Get the client property
     */
 	protected $fillable = [
        'group_name'
    ];

    public function GroupUser(){
        return $this->hasMany('App\GroupUser','group_id','id');
    }

    public function message(){
        return $this->hasMany('App\Message','group_id','id');
    }

    // public function user(){

    //     return $this->hasone('App\Admin','id','user_id');
    // }

    public function user()
    {
        return $this->morphTo(__FUNCTION__, 'user_type', 'user_id');
    }
}
