<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblm_shifts', function (Blueprint $table) {
            $table->id();
            $table->date('week_of');        // this is the selected date; just do +7
            $table->string('added_to');     // ex. ['2020-07-01','2020-07-02'] insert the date; format: array

            $table->foreignId('position_id')->constrained('tblm_positions');

            $table->time('start', 0);
            $table->time('end', 0);

            //employee_id int
            // if auto calculate hasn't been ticked, supply "paid_hours" is enabled
            // field
            // $table->boolean('auto_calculate')->default(false); // (subject for removal)
            $table->integer('paid_hours');

            $table->string('color')->isNullable();
            $table->mediumText('description');
            $table->boolean('notify_affected_employees')->default(false);

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
        Schema::dropIfExists('tblm_shifts');
    }
}
