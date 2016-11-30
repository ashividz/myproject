<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpJobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emp_job', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('emp_id')->references('id')->on('employees')->onDelete('cascade');
            $table->string('emp_no')->unique();
            $table->integer('job_id')->references('id')->on('emp_jobs')->onDelete('cascade');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->integer('termination_id')->references('id')->on('emp_terminations')->onDelete('cascade')->nullable();
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
        Schema::drop('emp_job');
    }
}
