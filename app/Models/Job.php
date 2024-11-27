<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    
    protected $table = 'jobs';
    protected $fillable = [
        'employee_id'
        ,'job_title',
        'salary',
        'city',
        'area',
        'pincode',
        'job_posting_location',
        'people_to_hire',
        'recruitment_timeline',
        'schedule',
        'job_type',
        'benefits',
        'job_description',
        'pay_minimum',
        'pay_maximum',
        'communication_preference_email',
        'job_status',
        'application_deadline',
        'planned_start_date',
        'start_date',
        'cv_option',

        
    ];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }
}
