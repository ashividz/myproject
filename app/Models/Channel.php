<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Channel extends Model
{
    //use SoftDeletes;
    public function sources()
    {
    	return $this->hasMany(Source::class);
    }
}
