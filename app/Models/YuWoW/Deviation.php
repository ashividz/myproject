<?php

namespace App\Models\YuWoW;

use Illuminate\Database\Eloquent\Model;

class Deviation extends Model
{
    protected $table = 'yuwow_alpha_1_0.dt_cust_deviations';

    public function user()
    {
    	return $this->belongsTo(User::class, 'dcd_cust_id');
    }
}
