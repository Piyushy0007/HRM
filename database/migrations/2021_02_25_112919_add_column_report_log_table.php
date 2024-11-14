<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnReportLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table('tblm_report_logs', function(Blueprint $table) {
           //$table->foreignId('shift_id')->constrained('tblm_shifts')->after('report_type_id');
           // $table->string('report_subtype_id')->nullable()->afster('shift_id');
            //$table->time('time')->nullable()->after('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
