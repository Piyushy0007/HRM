<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblmEmployeeCheckingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblm_employee_checking', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id', 50);
            $table->time('start_time',0);
            $table->time('end_time',0);
            $table->time('break_time',0);
            $table->string('location',150);
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
        Schema::dropIfExists('tblm_employee_checking');
    }
}
