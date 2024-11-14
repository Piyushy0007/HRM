<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsDetect extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('detect_shift_duty', function(Blueprint $table) {
            $table->time('lunch_start')->nullable()->after('clock_out_time');
            $table->time('lunch_end')->nullable()->after('lunch_start');
            $table->time('break_start')->nullable()->after('lunch_end');
            $table->time('break_end')->nullable()->after('break_start');
        
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
