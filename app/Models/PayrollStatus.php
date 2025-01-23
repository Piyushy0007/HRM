<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories;
use Illuminate\Database\Eloquent\Model;

class PayrollStatus extends Model
{
    
    protected $table = 'payroll_status';
    protected $fillable = [
        'month',
        'year',
        'total_gross_salary',
        'total_net_salary',
        'total_tax',
        'total_basic_salary',
    ];
}