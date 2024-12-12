<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Candidate's name
            $table->string('phone_number'); // Candidate's phone number
            $table->string('email'); // Candidate's email
            $table->string('resume'); // Path to uploaded resume
            $table->unsignedBigInteger('job_number'); // Foreign key to jobs table
            $table->foreign('job_number')->references('job_number')->on('jobs')->onDelete('cascade');
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
        Schema::dropIfExists('applications');
    }
}
