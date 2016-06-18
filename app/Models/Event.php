<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public static function getLoginEvent()
    {
    	return self::find(1);
    }

    public static function getLogoutEvent()
    {
    	return self::find(2);
    }
}

/*create table events (
  id int(10) unsigned not null auto_increment,
  name varchar(30),
  description text,
  created_at datetime not null,
  updated_at datetime not null,
  primary key (id)  
);
*/