<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblmJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('job_title');
            $table->decimal('salary', 10, 2)->nullable();
            $table->string('city');
            $table->string('area')->nullable();
            $table->string('pincode', 10);
            $table->enum('job_posting_location', ['onsite', 'hybrid', 'remote']);
            $table->integer('people_to_hire');
            $table->date('recruitment_timeline')->nullable();
            $table->text('schedule')->nullable();
            $table->string('job_type');
            $table->text('benefits')->nullable();
            $table->text('job_description');
            $table->decimal('pay_minimum', 10, 2)->nullable();
            $table->decimal('pay_maximum', 10, 2)->nullable();
            $table->boolean('communication_preference_email')->default(true);

            // Add job_status field
            $table->boolean('job_status')->default(1); // 1 for open, 0 for closed
            
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
        Schema::dropIfExists('tblm_jobs');
    }
}