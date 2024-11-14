<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblmq_shift_employee', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained('tblm_shifts');
            $table->foreignId('employee_id')->constrained('tblm_employee');
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
        Schema::dropIfExists('tblmq_shift_employee');
    }
}
