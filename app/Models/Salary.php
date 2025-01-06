<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{

    protected $table = 'salaries';
    protected $fillable = [
        'employee_id',
        'basic_salary',
        'gross_salary',
        'net_salary',
        'salary_type',
        'payment_mode',
        'payment_date'
    ];

    // protected $appends = ['gross_salary'];

    // Gross salary is calculated as the sum of basic salary and net salary
    public function getGrossSalaryAttribute()
    {
        return $this->basic_salary + $this->net_salary;
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
