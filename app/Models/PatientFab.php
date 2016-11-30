<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientFab extends Model
{
    protected $table = "patient_fab";

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}