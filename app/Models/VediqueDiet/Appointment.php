<?php

namespace App\Models\VediqueDiet;

use Illuminate\Database\Eloquent\Model;
use DB;

class Appointment extends Model
{
    protected $connection = 'VediqueDiet';
    protected $table = 'patient_appointment';
    public $timestamps = false;
    
}
