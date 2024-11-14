<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportSubType extends Model
{
    use SoftDeletes;
    protected $table = 'tblm_report_subtype';
    protected $fillable = ['report_type_id','report_type_name'];
}
