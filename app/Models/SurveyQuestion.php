<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyQuestion extends Model
{
    use SoftDeletes;
    protected $table = "survey_questions";

    public function surveys()
    {
    	return $this->hasMany(Survey::class,'Q1', 'title');
    }

    public function answers() {

    	return $this->hasMany(SurveyAnswer::class, 'question_id');
    }

    public function comments(){
        return $this->hasMany(SurveyCommment::class,'question_id');
    }
}
