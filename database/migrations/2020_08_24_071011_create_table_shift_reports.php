<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableShiftReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // This will just capture every report being generated regardless if it's being printed or not
        Schema::create('tblmq_shift_reports', function (Blueprint $table) {
            $table->id();

            // These are the current static values
            //  - summary-employee
            //  - detailed-employee
            //  - summary-position
            //  - detailed-position
            $table->string('report_category', 100);

            // Date range
            $table->date('start_date');
            $table->date('end_date');

            $table->boolean('is_favorite');

            // The value from "updated_at" field will serve as the "last_run"
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
        Schema::dropIfExists('tblmq_shift_reports');
    }
}
