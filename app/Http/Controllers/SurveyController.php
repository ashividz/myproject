<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Patient;
use App\Models\Survey;
use App\Models\PatientSurvey;
use App\Models\PatientSurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveyAnswer;
use App\Models\SurveyComment;
use DB;

class SurveyController extends Controller
{
    protected $menu;
    public $daterange;
    public $start_date;
    public $end_date;

    public function __construct()
    {
        $this->menu = "quality";
        $this->daterange = isset($_REQUEST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/m/01 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');
    }

    public function patients()
    {
        $patients = Patient::select('patient_details.*', 'start_date', 'end_date')
                    ->with('lead', 'lead.cre')
                    ->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                            $join->on('patient_details.id', '=', 'f.patient_id');
                        })
                    ->whereBetween('end_date', array($this->start_date, $this->end_date))
                    ->orderBy('end_date', 'desc')
                    ->get();

        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'patients',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'patients'      =>  $patients,
            'i'             =>  '1'
        );

        return view('home')->with($data);
    }

    public function patientSurvey($id)
    {
        $patient = Patient::find($id);

        $questions = SurveyQuestion::get();

        $comments = SurveyComment::where('question_id',3)->get();
        
        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'survey',
            'patient'       =>  $patient,
            'questions'     =>  $questions,
            'reasons'       =>  $comments
        );

        return view('home')->with($data);
    }

    public function viewPatientSurvey($id)
    {
        $patient = Patient::with('surveys')->find($id);

        //dd($patient);

        //$surveys = PatientSurvey::where('patient_id', $id)->get();
        
        $data = array(
            'menu'          =>  'patient',
            'section'       =>  'partials.survey',
            'patient'       =>  $patient
        );

        return view('home')->with($data);
    }

    public function viewCrePatientSurvey($id)
    {
        $patient = Patient::with('cresurveys')->find($id);

        //dd($patient);

        //$surveys = PatientSurvey::where('patient_id', $id)->get();
        
        $data = array(
            'menu'          =>  'reports',
            'section'       =>  'patients.survey',
            'patient'       =>  $patient
        );

        return view('home')->with($data);
    }

    public function savePatientSurvey(Request $request)
    {
        //DB::transaction(function () use ($request){
            $survey = PatientSurvey::saveSurvey($request);

            $score = 0;
            $size = count($request->comment);
            
            for ($i=1; $i <= $size; $i++) { 
                if(!empty($request->answer[$i]))
                {
                    $answer = PatientSurveyAnswer::saveAnswer($survey->id, $i, $request->answer[$i], $request->comment[$i]);
                    $score += $answer->answer->assessment_value;
                }                    
            }

            $survey->score = $score;
            $survey->save();
            
            return $this->patients();

        //});
    }

    public function viewSurveys()
    {
        $surveys = PatientSurvey::with('answers')
                    ->whereBetween('created_at', array($this->start_date, $this->end_date))
                    ->get();
        
        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'viewSurveys',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'surveys'       =>  $surveys
        );

        return view('home')->with($data);
    }

    public function viewSurveySummary()
    {
    	$surveys = array();

    	$questions = SurveyQuestion::get();

    	foreach ($questions as $question) {

    		$survey['title'] = $question->title;
    		$survey['question'] = $question->question;
    		$survey['count'] = $this->getCount($question->id);
    		$survey['answers'] = $this->getResponses($question->id);
            //$survey['comments'] = $this->getComments($question->id);
    		array_push($surveys, $survey);
    	}

    	$surveys = json_encode($surveys);

    	//dd($surveys);

    	$data = array(
    		'surveys'		=>	$surveys,
    		'menu'			=>	$this->menu,
    		'section'		=>	'viewSurveySummary',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date
    	);

    	return view('home')->with($data);
    }

    public function viewSurveyResults()
    {
        $surveys = array();

        $questions = SurveyQuestion::get();
        foreach ($questions as $question) {
            
            $survey['title'] = $question->title;
            $survey['question'] = $question->question;
            $survey['count'] = $this->getCount($question->id);
            $survey['answers'] = $this->getResponses($question->id);
            $survey['comments'] = $this->getComments($question->id);
            array_push($surveys, $survey);
        }

        $surveys = json_encode($surveys);

        $q = SurveyQuestion::with('answers')->get();

        
        $data = array(
            'surveys'       =>  $surveys,
            'menu'          =>  $this->menu,
            'section'       =>  'viewSurveyResults',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date
        );

        return view('home')->with($data);
    }

    private function getCount($id){
       
        return PatientSurveyAnswer::where('question_id',$id)
                    ->whereBetween('created_at', array($this->start_date, $this->end_date))
                    ->count();
    }

    private function getResponses($id)
    {        
        return DB::table('survey_answers AS a')
                    ->select(DB::RAW('a.id, answer, COUNT(s.id) AS count'))
                    ->Join('patient_survey_answers AS s', 's.answer_id', '=', 'a.id')
                    ->where('s.question_id', $id)
                    ->whereBetween('s.created_at', array($this->start_date, $this->end_date))
                    ->groupBy('a.id', 'answer')
                    ->orderBy('a.id')
                    ->get();
    }

    private function getComments($id)
    {
        return DB::table('patient_survey AS s')
                ->select(DB::RAW('s.id, patient_id, s.nutritionist,comment AS comment, s.created_at'))
                ->leftjoin('patient_survey_answers AS psa',  function($join)
                    {
                        $join->on('s.id', '=', 'psa.patient_survey_id');
                    })  
                ->where('comment', '<>', '')
                ->where('comment','<>','None')
                ->where('psa.question_id', $id)  
                ->whereBetween('psa.created_at', array($this->start_date, $this->end_date)) 
                ->get();
    }

    public function viewNutritionistWiseSurvey()
    {
        $surveys = PatientSurvey::whereBetween('created_at', array($this->start_date, $this->end_date))
                    ->orderBy('id', 'desc')
                    ->limit(env('DB_LIMIT'))
                    ->get();  
        //dd($surveys);                          

        $summaries = PatientSurvey::select(DB::RAW('nutritionist, sum(score) AS score, count(*) AS total'))
                    ->whereBetween('created_at', array($this->start_date, $this->end_date))
                    ->groupBy('nutritionist')
                    ->get();

        $data = array(
            'menu'          =>  'service',
            'section'       =>  'viewSurveyResults',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'surveys'       =>  $surveys,
            'summaries'     =>  $summaries,
            'i'             => '1'
        );

        return view('home')->with($data);
    }

    public function survey()
    {
        $questions = SurveyQuestion::with('answers')->get();

        foreach ($questions as $question) {
            $answers = PatientSurveyAnswer::with('answer')
                        ->where('question_id', $question->id)
                        ->whereBetween('created_at', array($this->start_date, $this->end_date))
                        ->get();

            foreach ($question->answers as $answer) {
                $answer->count = $answers->where('answer_id', $answer->id)->count();
            }
            $question->total_answers_count = $answers->count();
            $question->comments = $answers;

            //dd($answers);
        }

        //dd($questions);

        $data = array(
            'menu'          =>  'reports',
            'section'       =>  'quality.survey',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'questions'     =>  $questions
        );

        return view('home')->with($data);
    }

    public function comments(Request $request)
    {

        return PatientSurveyAnswer::with('survey.patient.lead', 'answer', 'question')
                ->where('question_id', $request->id)
                ->whereBetween('created_at', array($request->start_date, $request->end_date))
                ->where('comment', '<>', 'None')
                ->get();
    }

    /*
        Nutritionist wise survey report
    */

    public function nutritionist()
    {

        $surveys =  PatientSurvey::with('answers')
                ->whereBetween('created_at', array('2015-11-01', $this->end_date))
                ->groupBy('nutritionist')
                ->limit(10)
                ->get();

        dd($surveys);
    }  
    
    public function viewcustomersatisfaction()
    {
        $surveys = array();

        $answers = SurveyAnswer::where('question_id',3)->get();
        foreach ($answers as $answer) {
            
            $survey['title'] = $answer->answer;
            $survey['count'] = PatientSurveyAnswer::where('answer_id', $answer->id)
                                                    ->whereBetween('created_at', array($this->start_date, $this->end_date)) 
                                                    ->count();
            $survey['patients'] = DB::table('patient_survey_answers AS psa')
                                    ->select(DB::RAW('patient_id,comment,nutritionist'))
                                    ->leftjoin('patient_survey AS ps',  function($join)
                                    {
                                        $join->on('ps.id', '=', 'psa.patient_survey_id');
                                    }) 
                                    ->where('answer_id', $answer->id)
                                    ->whereBetween('psa.created_at', array($this->start_date, $this->end_date)) 
                                    ->get();
            array_push($surveys, $survey);
        }

        $surveys = json_encode($surveys);
                
        $data = array(
            'menu'          =>  $this->menu,
            'surveys'        =>  $surveys,
            'section'       =>  'viewCustomerSatisfaction',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date
        );

        return view('home')->with($data);
    }
}
