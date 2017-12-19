<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class PatientBreak extends Model
{
    //use SoftDeletes;

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
    
}
