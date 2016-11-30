<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Registration extends Model
{
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function mode()
    {
        return $this->belongsTo(PaymentMode::class, 'mode_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}