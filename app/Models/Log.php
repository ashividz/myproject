<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public function getOldValueAttribute($value)
    {
        return json_decode($value);
    }

    public function getNewValueAttribute($value)
    {
        return json_decode($value);
    }

    public function owner()
    {
        return $this->morphTo();
    }

}