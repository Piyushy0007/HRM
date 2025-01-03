<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id(); // Automatically generates an auto-incrementing primary key column
            $table->foreignId('employee_id')->constrained()->onDelete('cascade'); // Foreign key for employee_id
            $table->string('email'); // Column for employee email
            $table->string('leave_type'); // Column for type of leave
            $table->date('start_date'); // Start date for leave
            $table->date('end_date'); // End date for leave
            $table->string('reason'); // Column for the reason
            $table->date('leave_date')->default(now()); // Date when leave request is created
            $table->tinyInteger('status')->default(0); // Status with default value of 0 (pending)
            $table->timestamps(); // Automatically generates 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_requests');
    }
}
