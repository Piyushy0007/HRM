<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Client extends Authenticatable
{
   use SoftDeletes, Notifiable;
    protected $table = 'tblm_clients';
    //protected $guard = 'clients';
    /**
     * Get the client property
     */
 	protected $fillable = [
        'firstname','lastname','email', 'police_email','zip','phone','password','website','client_image','switch',
    ];

        /**

     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    protected $dates = ['deleted_at'];
    
    // public function ClientProperties()
    // {
    //     return $this->hasMany('App\ClientProperties','client_id','id');
    // }

    public function property()
    {
        return $this->hasMany('App\ClientProperties');
    }



    /**
     * Get all of the post's comments.
     */
    public function messages()
    {
        return $this->morphMany(Message::class, 'messageable');
    }

    public function groups()
    {
        return $this->morphOne(Group::class, 'user');
    }

    public function groupuser()
    {
        return $this->morphOne(GroupUser::class, 'groupable');
    }

    public function seen()
    {
        return $this->morphOne(Mesagelastseen::class, 'seenable');
    }


}


