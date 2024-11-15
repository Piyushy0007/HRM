<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('tblm_employee')->onDelete('cascade');
            $table->string('subject');
            $table->text('description');
            $table->date('created_date')->default(now());
            $table->date('bill_date')->nullable();
            $table->tinyInteger('status')->default(0); // pending (0), approved (1), reject (2)
            $table->unsignedBigInteger('approval_employee_id')->nullable();
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
        Schema::dropIfExists('client_requests');
    }
}
