<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInreportLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblm_report_logs', function(Blueprint $table) {
            $table->string('police_report')->nullable()->after('time');
            $table->string('police_number')->nullable()->after('police_report');
            $table->string('report_image')->nullable()->after('police_number');
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
