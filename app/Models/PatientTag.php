<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientTag extends Model
{
    use SoftDeletes;

    protected $table = "patient_tag";


    public function tag()
    {
    	return $this->belongsTo(Tag::class);
    }
    
}
