<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function lead()
    {
    	return $this->belongsTo(Lead::class);
    }

    public function recipients()
    {
    	return $this->hasMany(MessageRecipient::class);
    }
}
