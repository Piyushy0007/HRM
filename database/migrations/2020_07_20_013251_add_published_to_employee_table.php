<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublishedToEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblm_employee', function (Blueprint $table) {
            $table->boolean('new_schedule_published')->default(false);
            $table->boolean('published_shift_is_changed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblm_employee', function (Blueprint $table) {
            $table->boolean('new_schedule_published');
            $table->boolean('published_shift_is_changed');
        });
    }
}
