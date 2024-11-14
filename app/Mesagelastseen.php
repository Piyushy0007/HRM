<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mesagelastseen extends Model
{
    protected $table = 'table_tblm_message_seen';
    protected $guarded = [ 'id' ];

    public function seenable()
    {
        return $this->morphTo(__FUNCTION__, 'seenable_type','seenable_id');
    }
}
