<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('create-requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('selected_employee');
            $table->string('request_type');
            $table->text('reason');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('create-requests');
    }
}
