<?php

namespace App\Models\YuWoW;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'yuwow_alpha_1_0.users';

    public function healthtrack()
    {
    	return $this->hasMany(Healthtrack::class, 'dch_cust_id', 'Id');
    }

    public function deviation()
    {
    	return $this->hasMany(Deviation::class, 'dcd_cust_id', 'Id');
    }

    public function diary()
    {
    	return $this->hasMany(Diary::class, 'dch_cust_id', 'Id');
    }

    public function fitness()
    {
    	return $this->hasMany(Fitness::class, 'dcf_cust_id', 'Id');
    }
}
