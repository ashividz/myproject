<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Patient;
use DB;
use Auth;

class CrePatientSurveyAnswer extends Model
{
	//protected $table = "patient_survey_answers";

	public function answer()
	{
		return $this->belongsTo(CreSurveyAnswer::class);
	}

	public function question()
	{
		return $this->belongsTo(CreSurveyQuestion::class);
	}

	public function survey()
	{
		return $this->belongsTo(CrePatientSurvey::class, 'patient_survey_id');
	}

	public static function saveAnswer($patient_survey_id, $question_id, $answer_id, $comment)
	{
		$answer = new CrePatientSurveyAnswer;
		$answer->patient_survey_id = $patient_survey_id;
		$answer->question_id = $question_id;
		$answer->answer_id = $answer_id;
		$answer->comment = $comment;
		$answer->save();

		return $answer;
	}
}