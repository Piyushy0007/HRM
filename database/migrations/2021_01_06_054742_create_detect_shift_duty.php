<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetectShiftDuty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detect_shift_duty', function (Blueprint $table) {
            $table->id();           
            $table->string('employee_id');
            $table->time('clock_in_time');
            $table->time('clock_out_time');
            $table->string('onShift');
            $table->date('date');
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
        Schema::dropIfExists('detect_shift_duty');
    }
}
