<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('tblm_employee', function (Blueprint $table) {
        $table->id();
        $table->string('firstname', 50);
        $table->string('lastname', 50);
        $table->string('phone');
        $table->string('phone2')->nullable();
        $table->string('email')->unique()->nullable();
        $table->string('password');
        $table->string('plain_password');

        $table->string('address');
        $table->string('address2')->nullable();
        $table->string('city');
        $table->string('state');
        $table->string('zip');

        $table->tinyInteger('max_weekly_hours');
        $table->tinyInteger('max_weekly_days');
        $table->tinyInteger('max_day_hours');
        $table->tinyInteger('max_day_shifts');
        $table->float('pay_rate', 8, 2);
        $table->date('hired_date');
        $table->tinyInteger('priority_group');
        $table->boolean('enable_screen_reader')->default(false);
        $table->integer('role_id');

        // New Fields
        $table->string('gender');
        $table->string('employee_status');
        $table->string('employee_type');
        $table->string('location_type');
        $table->string('position');
        $table->string('education_detail');
        $table->string('experience_duration');

        $table->timestamps();
        $table->softDeletes();
    });
}
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblm_employee');
    }    
}
