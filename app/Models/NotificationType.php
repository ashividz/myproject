<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon;

class NotificationType extends Model
{
    public function object()
    {
        return $this->belongsTo(Object::class);
    }
}