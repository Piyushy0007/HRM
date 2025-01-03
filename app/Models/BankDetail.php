<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    

    protected $table = 'bank_details';
    protected $fillable = [
        'employee_id',
        'bank_name',
        'account_number',
        'ifsc_code',
        'branch_name',
    ];

    // Relationship with Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
