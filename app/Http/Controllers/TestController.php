<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Crypt;
use Hash;
use App\Models\Survey;
use App\Models\PatientSurvey;
use App\Models\PatientSurveyAnswer;
use App\Models\LeadSource;
use App\Models\PatientPrakriti;
use App\Models\Patient;
use App\Models\Lead;
use DB;
use App\DND;

class TestController extends Controller
{
	public function index()
	{
		$patients = Patient::select('patient_details.*')
					->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                        $join->on('patient_details.id', '=', 'f.patient_id');
                    })
                    ->where('f.entry_date', '>=', '2016-01-27')
                    ->get();

        foreach ($patients as $patient) {
        	$lead = Lead::find($patient->lead_id);
        	$lead->status_id = 5;
        	$lead->save();

        	echo $patient->entry_date." <a href='/lead/".$patient->lead->id."/viewDetails' target='_blank'>".$patient->lead->name."</a> = ".$patient->lead->status_id ."<p>";
        }
	}

	public function dnd()
	{
		$dnd = new DND;

		$leads = Lead::
				whereNull('dnd')
				->limit(100)
				->get();
		
		foreach ($leads as $lead) {
			if($dnd->validate($lead->phone) || $dnd->validate($lead->mobile)){
				//$status = true;
				Lead::updateDNDStatus($lead, 1);
				echo $lead->name;
			} else {
				$status = false;
			}

			
		}
		
			
	}

	public function index1($id=12675)
	{
		
		$constution = DB::table('constitution')
					->where('clinic', 'C1')
					->where('registration_no', $id)
					->first();
		
		$details =  json_decode($constution->detail);

		dd($details);

		/*$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '1'));
		$prakriti->question_id = 1;
		$prakriti->prakriti_id = $details->lifestyle;
		$prakriti->save();*/


		$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '2'));
		$prakriti->question_id = 2;
		$prakriti->prakriti_id = $details->activity;
		$prakriti->save();

		$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '3'));
		$prakriti->question_id = 3;
		$prakriti->prakriti_id = $details->bodyframe;
		$prakriti->save();

		$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '4'));
		$prakriti->question_id = 4;
		$prakriti->prakriti_id = $details->circulation;
		$prakriti->save();

		$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '5'));
		$prakriti->question_id = 5;
		$prakriti->prakriti_id = $details->appetite;
		$prakriti->save();

		/*$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '6'));
		$prakriti->question_id = 6;
		$prakriti->prakriti_id = $details->bowel;
		$prakriti->save();*/

		$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '7'));
		$prakriti->question_id = 7;
		$prakriti->prakriti_id = $details->digestion;
		$prakriti->save();

		$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '8'));
		$prakriti->question_id = 8;
		$prakriti->prakriti_id = $details->hair;
		$prakriti->save();

		$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '9'));
		$prakriti->question_id = 9;
		$prakriti->prakriti_id = $details->skin;
		$prakriti->save();

		$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '10'));
		$prakriti->question_id = 10;
		$prakriti->prakriti_id = $details->sleep;
		$prakriti->save();

		$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '11'));
		$prakriti->question_id = 11;
		$prakriti->prakriti_id = $details->sweat;
		$prakriti->save();

		$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '12'));
		$prakriti->question_id = 12;
		$prakriti->prakriti_id = $details->taste;
		$prakriti->save();

		$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '13'));
		$prakriti->question_id = 13;
		$prakriti->prakriti_id = $details->thirst;
		$prakriti->save();

		$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '14'));
		$prakriti->question_id = 14;
		$prakriti->prakriti_id = $details->voice;
		$prakriti->save();


		$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '15'));
		$prakriti->question_id = 15;
		$prakriti->prakriti_id = $details->weather;
		$prakriti->save();

		$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '16'));
		$prakriti->question_id = 16;
		$prakriti->prakriti_id = $details->weight;
		$prakriti->save();

		$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '17'));
		$prakriti->question_id = 17;
		$prakriti->prakriti_id = $details->temperament;
		$prakriti->save();

		/*$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '18'));
		$prakriti->question_id = 18;
		$prakriti->prakriti_id = $details->social;
		$prakriti->save();*/

		$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '19'));
		$prakriti->question_id = 19;
		$prakriti->prakriti_id = $details->speech;
		$prakriti->save();

		$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '20'));
		$prakriti->question_id = 20;
		$prakriti->prakriti_id = $details->memory;
		$prakriti->save();









		//dd($prakriti);

		dd(Patient::with('prakritis')->find($id));

		dd($details);

		/*$surveys = Survey::get();

		foreach ($surveys as $survey) {
			$ps = new PatientSurvey;
			$ps->patient_id = $survey->patient_id;
			$ps->nutritionist = $survey->nutritionist;
			$ps->source = $survey->source;			
			$ps->score = $survey->score;
			$ps->created_by = $survey->user;
			$ps->created_at = $survey->created_at;
			$ps->updated_at = $survey->updated_at;
			$ps->save();

			$psa = new PatientSurveyAnswer;
			$psa->patient_survey_id = $ps->id;
			$psa->question_id = 1;
			$psa->answer_id = $survey->Q1;
			$psa->comment = $survey->Q1comment;
			$psa->save();

			$psa = new PatientSurveyAnswer;
			$psa->patient_survey_id = $ps->id;
			$psa->question_id = 2;
			$psa->answer_id = $survey->Q2;
			$psa->comment = $survey->Q2comment;
			$psa->save();

			$psa = new PatientSurveyAnswer;
			$psa->patient_survey_id = $ps->id;
			$psa->question_id = 3;
			$psa->answer_id = $survey->Q3;
			$psa->comment = $survey->Q3comment;
			$psa->save();

			$psa = new PatientSurveyAnswer;
			$psa->patient_survey_id = $ps->id;
			$psa->question_id = 4;
			$psa->answer_id = $survey->Q4;
			$psa->comment = $survey->Q4comment;
			$psa->save();

			$psa = new PatientSurveyAnswer;
			$psa->patient_survey_id = $ps->id;
			$psa->question_id = 5;
			$psa->answer_id = $survey->Q5;
			$psa->comment = $survey->Q5comment;
			$psa->save();
		}

		dd($surveys);


		/*$leads = Lead::with('query1')
				->whereNull('source_id')
				->whereNotNull('query_id')
				->limit(1000)
				->get();


		//dd($leads);
		$i = 1;

		foreach ($leads as $lead) {
			$source = Source::where('source', $lead->query1->source)
						->orWhere('source1', $lead->query1->source)
						->orWhere('source2', $lead->query1->source)
						->orWhere('source3', $lead->query1->source)
						->first();

			echo $i++ . " <a href='lead/" . $lead->id . "/viewDetails' target='_blank'>" . $lead->name . "</a> ";

			$leadSource = new LeadSource;

			$leadSource->lead_id = $lead->id;
			$leadSource->clinic = $lead->clinic;
			$leadSource->enquiry_no = $lead->enquiry_no;
			
			if ($source) {
				$leadSource->source = $source->id;
				$lead->source_id = $source->id;
			}
			else
			{
				$leadSource->source = 5;
				$lead->source_id = 5;
			}
			
			$leadSource->created_at = $lead->query1->date;
			$leadSource->remarks = $lead->query1->query . "";
			$leadSource->save();

			
			$lead->save();
			echo $lead->query1->source;

			
			echo "<p>";

		}*/
	}

	public function show()
	{
		return view('test');
	}

	public function encrypt($id)
	{
		$q = "eyJpdiI6InR1VG1tVUw5Vmx0YVNGK1RHQnF0aHc9PSIsInZhbHVlIjoieFFYY0F2eW5TNmJXMjIzejhoUlwvditFRG5NeWdRUHZXeDdsNnVZaitFcVlSRGFyWUdXY1IyRmI3aU5WcGFPU0YiLCJtYWMiOiI5ZDBmOWZmZTM4MDUyOTlmZWNiM2Q4YWRiNzE1OTA2M2QwYTExY2NlMjg5MTczNDhiNTg4ZmViNGVkYmU2ZGVmIn0=
";
		echo hash('md5',$id)."<p>";
		return Crypt::decrypt($q);
	}
}