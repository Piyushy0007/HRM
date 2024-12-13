<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{

    protected $table = 'applications';
    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'resume',
        'job_number',
        'location', // New field
        'applied_date',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
