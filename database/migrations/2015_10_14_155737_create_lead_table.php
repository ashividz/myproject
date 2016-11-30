<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('clinic', 2);
            $table->integer('enquiry_no');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('mobile', 15)->nullable();
            $table->string('phone', 15)->unique()->nullable();
            $table->string('email_alt')->nullable();
            $table->string('skype')->nullable();
            $table->string('country', 3)->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable();
            $table->string('profession')->nullable();
            $table->string('gender', 6)->nullable();
            $table->integer('height')->nullable();
            $table->decimal('weight', 6,3)->nullable();
            $table->dateTime('entry_date');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(array('clinic', 'enquiry_no'));
            $table->index(array('clinic', 'enquiry_no'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('marketing_details');
    }
}
