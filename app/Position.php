<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
	use SoftDeletes;

	protected $table = 'tblm_positions';
    protected $guarded = [ 'id' ];
}
