<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TblmViolation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblm_violation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('tblm_employee')->nullable();
            $table->foreignId('property_id')->constrained('tblmq_client_properties')->nullable();
            $table->foreignId('checked_id')->constrained('tblm_employee_checking')->nullable();
            $table->foreignId('shift_id')->constrained('tblm_shifts')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->date('violation_date')->nullable(); 
            $table->string('violation_status')->nullable();
            $table->timestamps();
            $table->SoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tblm_violation');
    }
}
