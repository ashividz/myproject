<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/* alter size of name fild */
class PatientEatingtip extends Model
{   
    protected $table = "patient_eatingtips";
    use SoftDeletes;
    protected $fillable = ['name'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
