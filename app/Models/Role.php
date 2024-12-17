<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'tblm_roles';

    protected $fillable = [
        'role_name',
        'status',
    ];

    // Relationship with Employees
    public function employees()
    {
        return $this->hasMany(Employee::class, 'role_id');
    }
}