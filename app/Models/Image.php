<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

    protected $table = 'images';
    protected $fillable = ['client_request_id', 'image_path'];

    public function clientRequest()
    {
        return $this->belongsTo(ClientRequest::class, 'client_request_id');
    }
}