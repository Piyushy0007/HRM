<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asset', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('serial_number');
            $table->string('brand_name');
            $table->string('model_number');
            $table->string('ram');
            $table->string('storage_capacity');
            $table->string('imei_number');
            $table->string('ip_address');
            $table->string('previous_state');
            $table->string('tag');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
