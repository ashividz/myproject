<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    public function answers($q)
    {
    	return $this->hasMany(SurveyAnswer::class, 'question_id',$q);
    }
}
