<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suit extends Model
{
    protected $table = 'suit_ntsuit';

    protected $fillable = [
        'patient_id',
        'suit',
        'not_suit',
        'trial_plan',
        'remark',
        'deviation',
        'created_by'
    ];

    public function patient()
    {
    	return $this->hasOne(Patient::class);
    }
}