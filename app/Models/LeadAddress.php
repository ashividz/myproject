<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\AuditingTrait;

class LeadAddress extends Model
{
    use AuditingTrait;
    
    protected $fillable=[
    	'name',
    	'address',
    	'country',
    	'state',
    	'city',
    	'zip',
    	'lead_id',
    	'created_by',
      'address_type',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function region()
    {
        return $this->hasOne(Region::class, 'region_code', 'state');
    }

    public function m_country()
    {
        return $this->hasOne(Country::class, 'country_code', 'country');
    }
}

    /*
    create table lead_addresses (
      id int(15) not null auto_increment,
      lead_id int(15) not null,
      address_type varchar(30) not null,
      name varchar(50) not null,
      address varchar(200) not null,
      city varchar(20) not null,
      state varchar(30) not null,
      zip varchar(7) not null,
      country varchar(50) not null,
      created_by int(3) not null,
      created_at datetime not null,
      updated_at datetime not null,
      primary key (id)
    );
    
alter table carts add column shipping_address_id int(11) default null;
*/
