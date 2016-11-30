<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Patient;
use DB;
use Auth;

class PatientSurvey extends Model
{
	protected $table = "patient_survey";

	public function patient()
	{
		return $this->belongsTo(Patient::class);
	}

	public function answers()
	{
		return $this->hasMany(PatientSurveyAnswer::class);
	}

	public static function saveSurvey($request)
	{
		$survey = new PatientSurvey;
		$survey->patient_id = $request->patient_id;
		$survey->nutritionist = $request->nutritionist;
		$survey->source = $request->source;
		$survey->created_by = Auth::user()->employee->name;
		$survey->save();

		return $survey;
	}
}