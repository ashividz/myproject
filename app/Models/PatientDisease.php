<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientDisease extends Model
{
    protected $fillable = [
        'patient_id',
        'disease_id'
    ];

    protected $table = 'patient_disease';
}