<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories;
use Illuminate\Database\Eloquent\Model;

class SalaryDisbursed extends Model
{
    

    protected $table = 'salary_disbursements';

    protected $fillable = [
        'employee_id',
        'salary_id',
        'month',
        'year',
        'disbursed_amount',
        'status',
        'payment_date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function salary()
    {
        return $this->belongsTo(Salary::class);
    }
}
