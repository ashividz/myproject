<?php

namespace App\Models\YuWoW;

use Illuminate\Database\Eloquent\Model;

use App\Models\YuWoW\User;

class Healthtrack extends Model
{
    protected $table = 'yuwow_alpha_1_0.dt_cust_healthtrack';

    public function user()
    {
    	return $this->belongsTo(User::class, 'dch_cust_id');
    }

    public static function getWeight($email, $date)
    {
    	$user = User::where('user_login', 'like', $email)->first();

        if ($user) {
            
            return Healthtrack::where('dch_cust_id', $user->Id)
                        ->where('dch_date_recording', $date)
                        ->first();
        }

    	
    }
}
