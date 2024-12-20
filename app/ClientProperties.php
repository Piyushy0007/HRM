<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientProperties extends Model
{
	use SoftDeletes;
   protected $table = 'tblmq_client_properties';
   protected $fillable = [
      'client_id','address','lat','long','coordinates'
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

