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
        Schema::create('payroll_status', function (Blueprint $table) {
            $table->id();
            $table->integer('month');
            $table->string('year');
            $table->decimal('total_gross_salary', 15, 2);
            $table->decimal('total_net_salary', 15, 2);
            $table->decimal('total_tax', 15, 2);
            $table->decimal('total_basic_salary', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_status');
    }
};
