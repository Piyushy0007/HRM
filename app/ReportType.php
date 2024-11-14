<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportType extends Model
{
    use SoftDeletes;
    protected $table = 'tblm_report_type';
    protected $fillable = ['report_name'];
}
