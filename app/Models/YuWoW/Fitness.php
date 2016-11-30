<?php

namespace App\Models\YuWoW;

use Illuminate\Database\Eloquent\Model;

class Fitness extends Model
{
    protected $table = 'yuwow_alpha_1_0.dt_cust_fitness_brief';

    public function user()
    {
    	return $this->belongsTo(User::class, 'dcf_cust_id');
    }
}
