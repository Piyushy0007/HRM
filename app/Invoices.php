<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    protected $table = 'invoices';
    protected $guarded = [ 'id' ];
    public $fillable = [
        'invoice_amount',
        'client_id',
        'status',
        'cron_status',
        'date_posted'
    ];

    public function client(){
        return $this->belongsTo('App\Client','client_id');
    }
}
