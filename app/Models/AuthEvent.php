<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthEvent extends Model
{
    //
}

/*create table auth_events (
  id int(10) unsigned not null auto_increment,
  user_id int(4) not null, 
  event_id int(4) not null,
  ip varchar(64), 
  created_at datetime not null,
  updated_at datetime not null,
  primary key (id)  
);*/