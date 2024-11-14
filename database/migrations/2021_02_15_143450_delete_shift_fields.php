<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteShiftFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('tblm_shifts', function(Blueprint $table) {
            $table->dropColumn(['lunch_start','lunch_end','break_start','break_end']);        
        });
         Schema::table('detect_shift_duty', function(Blueprint $table) {
             $table->dropColumn(['lunch_start','lunch_end','break_start','break_end']);
        
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
