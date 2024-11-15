<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Employee extends Model
{
    use SoftDeletes,Notifiable;

    protected $table = 'tblm_employee';
    protected $guarded = [ 'id' ];
    protected $hidden = [ 'password' ];


    public function getNameAttribute($value)
    {
        return $this->firstname + " " + $this->lastname;
    }

    public function next()
    {
        return Employee::with('position')->where('id', '>', $this->id)->orderBy('id', 'asc')->first();
    }

    public function prev()
    {
        return Employee::with('position')->where('id', '<', $this->id)->orderBy('id', 'desc')->first();
    }

    /**
     * Get the positions of the employee
     */
    public function position()
    {
        return $this->hasMany('App\EmployeePosition');
    }


    public function shifts(){
        return $this->belongsToMany('App\Models\Shift', 'tblmq_shift_employee');
    }

    public function timeoff(){
        return $this->hasMany('App\Timeoff');
    }

    public function message() {
        return $this->belongsToMany('App\Message', 'tblq_message');
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

    public function joiningLetters()
    {
        return $this->hasOne(JoiningLetter::class, 'employee_id', 'id');
    }

    public function attendances() {
        return $this->hasMany('App\Models\Attendance', 'employee_id');
    }
    public function leaveRequests()
{
    return $this->hasMany('App\Models\LeaveRequest');
}
public function experiences()
{
    return $this->hasMany('App\Models\Experience');
}

    
    
}
