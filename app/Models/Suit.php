<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suit extends Model
{
    protected $table = 'suit_ntsuit';

    public function patient()
    {
    	return $this->hasOne(Patient::class);
    }
}