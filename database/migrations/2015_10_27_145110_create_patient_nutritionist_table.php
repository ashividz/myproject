<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientNutritionistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_nutritionists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->references('id')->on('patient_details')->onDelete('cascade');
            $table->string('clinic', 2);
            $table->integer('registration_no');
            $table->string('nutritionist');
            $table->boolean('secondary');
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
        Schema::drop('patient_nutritionists');
    }
}
