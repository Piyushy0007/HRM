<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Preferences extends Model
{
    use SoftDeletes;

	protected $table = 'tblm_employeework_preferences';
    protected $guarded = [ 'id' ];
}


