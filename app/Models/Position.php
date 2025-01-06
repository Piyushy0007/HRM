<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
	use SoftDeletes;

	protected $table = 'tblm_positions';

	protected $fillable = [
        'position',
        'created_at',
    ];
	
    // protected $guarded = [ 'id' ];
	public function employees()
    {
        return $this->hasOne(Employee::class, 'position');
    }
}
