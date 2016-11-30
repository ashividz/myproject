<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pin', 6)->unique();
            $table->string('city');
            $table->string('state');
            $table->boolean('pickup');
            $table->boolean('delivery');
            $table->boolean('oda')->nullable();
            $table->boolean('opa')->nullable();
            $table->boolean('cod');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cods');
    }
}
