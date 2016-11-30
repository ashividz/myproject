<?php

namespace App\Models\Dialer;

use Illuminate\Database\Eloquent\Model;

class DialerCallBack extends Model
{
    protected $connection = 'pgsql';
    protected $table 	  = 'ct_callbacks';

    public function user()
    {
    	return $this->belongsTo(User::class, 'username','username');
    }
}
