<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblmReportLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblm_report_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('tblm_employee')->nullable();
            $table->foreignId('property_id')->constrained('tblmq_client_properties')->nullable();
            $table->foreignId('report_type_id')->constrained('tblm_report_type')->nullable();
            $table->string('location')->nullable();
            $table->date('date')->nullable();
            $table->string('status')->nullable();  
            $table->timestamps();
            $table->SoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblm_report_logs');
    }
}
