<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/* 
2016-12-27
alter table patient_eatingtips modify name varchar(2048);
*/
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
