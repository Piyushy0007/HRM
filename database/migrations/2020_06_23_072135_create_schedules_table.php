<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblmq_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')->constrained('tblm_employee');
            $table->foreignId('client_properties_id')->constrained('tblmq_client_properties');

            $table->dateTime('time_in', 0);
            $table->dateTime('time_out', 0);

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
        Schema::dropIfExists('tblmq_schedules');
    }
}
