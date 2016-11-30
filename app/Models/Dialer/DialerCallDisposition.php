<?php

namespace App\Models\Dialer;

use Illuminate\Database\Eloquent\Model;

class DialerCallDisposition extends Model
{
    protected $connection = 'pgsql';
    protected $table 	  = 'ct_recording_log';

    public function master()
    {
    	return $this->belongsTo(Disposition::class, 'disposition','disponame');
    }

    public function user()
    {
    	return $this->belongsTo(User::class, 'username','username');
    }
}
