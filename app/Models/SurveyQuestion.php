<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    public function surveys()
    {
    	return $this->hasMany(Survey::class,'Q1', 'title');
    }

    public function answers() {

    	return $this->hasMany(SurveyAnswer::class, 'question_id');
    }
}
