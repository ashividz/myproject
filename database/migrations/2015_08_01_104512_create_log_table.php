<?php
/*create table logs (
  id int(10) not null auto_increment,
  user_id int(10) not null,
  owner_type varchar(255) not null,
  owner_id int(10) not null,
  old_value longtext not null,
  new_value longtext not null,
  type varchar(255) not null,
  route varchar(255) not null,
  ip varchar(16) not null,
  created_at timestamp not null default '2016-06-16 00:00:00',
  updated_at timestamp not null default '2016-06-16 00:00:00',
  primary key (id)
);*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('owner_type');
            $table->integer('owner_id');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->string('type');
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
        Schema::drop('logs');
    }
}
