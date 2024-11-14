<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftDate extends Model
{
	protected $table = 'tblmq_shift_added_to';
    protected $guarded = [ 'id' ];

    /**
     * Get the shift
     */
    public function shift()
    {
    	return $this->belongsTo('App\Models\Shift');
    }
}
