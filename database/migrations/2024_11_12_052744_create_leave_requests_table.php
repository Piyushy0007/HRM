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
            $table->id();  // Primary key
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');  // Foreign key for employee_id
            $table->string('leave_type');  // Leave type (sick, annual, etc.)
            $table->date('start_date');  // Start date of leave
            $table->date('end_date');  // End date of leave
            $table->string('reason');  // Reason for leave
            $table->date('leave_date')->default(DB::raw('CURRENT_DATE'));  // Automatically set to current date
            $table->tinyInteger('status')->default(0);  // Status, 0 = pending
            $table->timestamps();  // created_at and updated_at timestamps
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
