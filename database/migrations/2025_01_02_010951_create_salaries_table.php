<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('net_salary', 10, 2);
            $table->decimal('gross_salary', 10, 2);
            $table->string('allowances')->nullable(); // Allowances as a string
            $table->string('deductions')->nullable(); // Deductions as a string
            $table->string('salary_type'); // Monthly or Yearly
            $table->string('payment_mode'); // Payment mode (e.g., Bank Transfer, Cash)
            $table->date('payment_date'); // Payment date
            $table->decimal('pf_amount', 10, 2)->nullable(); // Provident Fund amount
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salaries');
    }
}
