<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayrateToTblmqEmployeePositions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblmq_employee_positions', function (Blueprint $table) {
            $table->double('pay_rate')->after('position_id')->default(0);
            $table->date('alter_date')->after('pay_rate')->default(null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblmq_employee_positions', function (Blueprint $table) {
            //
        });
    }
}
