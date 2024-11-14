<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TblmEmployeeChecking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblm_employee_checking', function($table) {
            $table->timestamp('check_date')->nullable()->after('shiftaction');
            $table->time('check_start')->nullable()->after('check_date');
            $table->time('check_end')->nullable()->after('check_start'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblm_employee_checking'); 
    }
}
