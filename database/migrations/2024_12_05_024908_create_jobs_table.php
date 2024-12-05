<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
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
            $table->string('recruitment_timeline')->nullable();
            $table->text('schedule')->nullable();
            $table->string('job_type');
            $table->text('benefits')->nullable();
            $table->text('job_description');
            $table->decimal('pay_minimum', 10, 2)->nullable();
            $table->decimal('pay_maximum', 10, 2)->nullable();
            $table->boolean('communication_preference_email')->default(true);
            $table->boolean('application_deadline')->default(false);
            $table->boolean('planned_start_date')->default(false);
            $table->date('start_date')->nullable();
            $table->boolean('job_status')->default(1);
            $table->tinyInteger('cv_option')->default(1);
            $table->enum('pay_rate_type', ['monthly', 'yearly'])->default('monthly');
            $table->unsignedBigInteger('job_number')->unique(); // Job number as 13-14 digit integer
            $table->string('email_1')->nullable();
            $table->string('email_2')->nullable();
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
        Schema::dropIfExists('jobs');
    }
}
