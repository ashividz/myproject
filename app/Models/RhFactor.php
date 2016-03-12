<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class RhFactor extends Model
{
    //use SoftDeletes;
    public function patient()
    {
    	return $this->hasOne(Patient::class);
    }
}
