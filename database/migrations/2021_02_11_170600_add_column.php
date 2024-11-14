<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblm_clients', function(Blueprint $table) {
           
            // $table->string('clientname')->nullable()->after('id');
            // $table->string('address')->nullable()->after('clientname');
            // $table->string('company')->nullable()->after('address');
            //$table->string('contactname')->nullable()->after('company');     

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
