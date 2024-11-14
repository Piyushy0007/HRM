<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblm_employee', function (Blueprint $table) {
            $table->rememberToken();
            // Note: as of this moment, the role is one-to-one, if its not, then create another table for role
            // so far the only role that I encountered were "admin", "client" and "guard"
            // For the admin role, just use the tblm_adminusers (????)
            $table->enum('role', ['guard','manager']); // first value will be set as the default
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
            //
        });
    }
}
