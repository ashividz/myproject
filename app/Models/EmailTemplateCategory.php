<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplateCategory extends Model
{
    public function templates()
    {
    	return $this->hasMany(EmailTemplate::class);
    }
}

/*
create table jobs (
  id bigint(20) unsigned not null auto_increment,
  queue varchar(255)not null,
  payload longtext not null,
  attempts tinyint(3) unsigned not null,
  reserved tinyint(3) unsigned not null,
  reserved_at int(10) unsigned default null,
  available_at int(10) unsigned not null,
  created_at int(10) unsigned not null,
  primary key (`id`),
  key `jobs_queue_reserved_reserved_at_index` (`queue`,`reserved`,`reserved_at`)
);


create table email_template_categories (
  id int(11) not null auto_increment,
  name varchar(255) not null,  
  created_at datetime not null default '0000-00-00 00:00:00',
  updated_at datetime not null default '0000-00-00 00:00:00',
  deleted_at datetime default null,
  primary key (id)
);

alter table email_templates add column email_template_category_id int(11) not null;
insert into email_template_categories (name,created_at,updated_at) values ('General',now(),now());
insert into email_template_categories (name,created_at,updated_at) values ('Promotional',now(),now());
update email_templates set email_template_category_id=1;


*/