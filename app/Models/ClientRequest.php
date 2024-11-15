<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientRequest extends Model
{

    protected $table = 'client_requests';
    protected $fillable = [
        'employee_id',
        'subject',
        'description',
        'created_date',
        'bill_date',
        'status',
        'approval_employee_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'client_request_id');
    }
}
