<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlertDateToTblmqEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblm_employee', function (Blueprint $table) {
            $table->date('alert_date')->after('pay_rate')->default(null)->nullable();
            $table->string('comment')->after('alert_date')->default(null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblm_employee', function (Blueprint $table) {
            //
        });
    }
}
