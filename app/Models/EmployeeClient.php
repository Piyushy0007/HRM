<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EmployeeClient extends Model
{
	use SoftDeletes;
    protected $table = 'tblm_employee_clients';
    protected $fillable = ['employee_id','client_id'];   
}