<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HerbTemplate extends Model
{
    public function herb()
    {
    	return $this->belongsTo(Herb::class);
    }

    public function unit()
    {
    	return $this->belongsTo(Unit::class);
    }

    public function mealtimes()
    {
    	return $this->hasMany(HerbTemplateMealtime::class);
    }
}