<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreSurveyQuestion extends Model
{
   public function answers() {

    	return $this->hasMany(CreSurveyAnswer::class, 'question_id');
    }
}
