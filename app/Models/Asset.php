<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
 
    protected $table = 'asset'; // Specify the table name
    protected $fillable = [
        'name',
        'category',
        'serial_number',
        'brand_name',
        'model_number',
        'ram',
        'storage_capacity',
        'imei_number',
        'ip_address',
        'previous_state',
        'tag',
    ];
}
