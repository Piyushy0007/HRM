<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryDisbursedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_disbursements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->unsignedBigInteger('salary_id');
            $table->foreign('salary_id')->references('id')->on('salaries')->onDelete('cascade');
            $table->integer('month')->length(4);
            $table->integer('year')->length(4);
            $table->decimal('disbursed_amount', 10, 2);
            $table->enum('status', ['Pending', 'Paid', 'Partially Paid']);
            $table->date('payment_date');
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
        Schema::dropIfExists('salary_disbursed');
    }
}
