<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblmParkingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblm_parking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('tblm_employee')->nullable();
            $table->foreignId('property_id')->constrained('tblmq_client_properties')->nullable();
            $table->string('parking_location')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_make')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('vehicle_image')->nullable();
            $table->string('status')->nullable();            
            $table->timestamps();
            $table->SoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblm_parking');
    }
}
