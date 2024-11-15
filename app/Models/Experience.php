<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $table = 'experiences';
    protected $fillable = ['employee_id', 'job_title', 'company_name', 'company_industry', 'start_date', 'end_date', 'responsibilities'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
