<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable implements JWTSubject
{
	use SoftDeletes, Notifiable;
	protected $table = 'tblmadminusers';
    protected $guard = 'users';
    protected $fillable = [
        'firstname', 'lastname','email', 'password',
    ];
    protected $guarded = [ 'id' ];
    protected $hidden = [ 'password', 'remember_token', ];
     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }


     /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    } 

    public function groups()
    {
        return $this->morphOne(Group::class, 'user');
    }

    public function messages()
    {
        return $this->morphMany(Message::class, 'messageable');
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
