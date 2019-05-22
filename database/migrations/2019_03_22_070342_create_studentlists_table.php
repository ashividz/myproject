<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studentlists', function (Blueprint $table) {
            $table->increments('id');
            $table->text('student_name');
            $table->text('father_name');
            $table->text('mobile_no');
            $table->text('address');
            $table->text('pin_code');
            $table->text('dob');
            $table->text('department');
            $table->text('description');
            $table->text('gender');
            $table->text('country');
            $table->text('state');
            $table->text('city');
            $table->text('web_url');
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
        Schema::dropIfExists('studentlists');
    }
}
