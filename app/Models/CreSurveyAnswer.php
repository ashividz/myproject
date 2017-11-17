<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreSurveyAnswer extends Model
{
    public function question(){
    	return $this->hasMany(CreSurveyQuestion::class, 'question_id');
    }
}