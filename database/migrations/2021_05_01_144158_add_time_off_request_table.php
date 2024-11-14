<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimeOffRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('time_off_request', function(Blueprint $table) {
            $table->string('whole_day')->default('false')->after('status');
            $table->time('from_time')->nullable()->after('whole_day');
            $table->time('to_time')->nullable()->after('from_time');           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_off_request');
    }
}
