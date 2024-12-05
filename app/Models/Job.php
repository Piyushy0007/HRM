<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'jobs';

    protected $fillable = [
        'employee_id',
        'job_title',
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
        'pay_rate_type',
        'email_1',
        'email_2',
        'job_number', // Add job_number to fillable
    ];

    // Auto-generate unique job_number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($job) {
            // Generate a 13-14 digit unique number
            do {
                $jobNumber = mt_rand(1000000000000, 9999999999999); // Generate 13-14 digit number
            } while (self::where('job_number', $jobNumber)->exists()); // Ensure it's unique

            $job->job_number = $jobNumber;
        });
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }
}
