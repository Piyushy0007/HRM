<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{

    protected $table = 'salaries';
    protected $fillable = [
        'basic_salary',
        'hra',
        'da',
        'pf',
        'tax',
        'gross_salary',
        'salary_type',
        'employee_id'
    ];

    // Automatic Gross Salary Calculation
    public static function calculateGrossSalary($basic_salary, $hra, $da, $pf, $tax)
    {
        return ($basic_salary + $hra + $da) - ($pf + $tax);
    }
}
