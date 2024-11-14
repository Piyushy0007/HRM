<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDetectShiftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detect_shift_duty', function($table) {
            $table->string('event_type')->nullable()->after('onShift');
            $table->string('location_from')->nullable()->after('event_type');
            $table->string('location_to')->nullable()->after('location_from');           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
