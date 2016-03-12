<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadSourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_source', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->unsignedInteger('source_id')->references('id')->on('lead_sources')->onDelete('cascade');
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
        Schema::drop('lead_source');
    }
}
