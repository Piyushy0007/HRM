<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreateRequest extends Model
{
    protected $table = 'create-requests';  
    protected $fillable = [
        'selected_employee',
        'request_type',
        'reason',
        'image_path',
    ];
}
