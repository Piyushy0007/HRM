<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblmq_client_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('tblm_clients');
            $table->string('name', 150);
            $table->string('lat');
            $table->string('long');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblmq_client_properties');
    }
}
