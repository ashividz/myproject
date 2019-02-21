<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    /*public function question(){
    	return $this->hasMany(SurveyQuestion::class, 'question_id');
    }*/

    public function question()
	{
		return $this->belongsTo(SurveyQuestion::class,'question_id')->withTrashed();
	}
}
