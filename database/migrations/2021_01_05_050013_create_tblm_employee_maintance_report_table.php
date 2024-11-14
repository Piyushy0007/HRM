<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblmEmployeeMaintanceReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblm_employee_maintance_report', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employee_id');
            $table->string('employee_name',100);
            $table->string('latitude',100);
            $table->string('longitude',100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblm_employee_maintance_report');
    }
}
