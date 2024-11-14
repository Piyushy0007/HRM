<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientAlertReport extends Model
{
	use SoftDeletes;
   protected $table = 'tblm_alertreport_users';
   protected $fillable = [
      'client_id','report_id'
  ];
    /**

 * The attributes that should be mutated to dates.

 *

 * @var array

 */

    protected $dates = ['deleted_at'];

  	public function client() {
       return $this->belongsTo('App\Client');
   }
    
  
}

