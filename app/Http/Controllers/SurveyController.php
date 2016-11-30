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
        
        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'survey',
            'patient'       =>  $patient,
            'questions'     =>  $questions
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
    		$survey['count'] = $this->getCount($question->title);
    		$survey['answers'] = $this->getResponses($question->id, $question->title);
            $survey['comments'] = $this->getComments($question->id, $question->title);
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
            $survey['count'] = $this->getCount($question->title);
            $survey['answers'] = $this->getResponses($question->id, $question->title);
            $survey['comments'] = $this->getComments($question->id, $question->title);
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

    private function getCount($title){
        
        return Survey::where($title, '>=', '1')
                    ->whereBetween('created_at', array($this->start_date, $this->end_date))
                    ->count();
    }

    private function getResponses($id, $title)
    {
    	return DB::table('survey_answers AS a')
    			->select(DB::RAW('a.id, answer, COUNT(s.id) AS count'))
    			->leftJoin('surveys AS s', $title, '=', 'a.id')
    			->where('question_id', $id)
                ->whereBetween('s.created_at', array($this->start_date, $this->end_date))
    			->groupBy('s.'.$title, 'answer')
    			->orderBy('a.id')
    			->get();
    }

    private function getComments($id, $title)
    {
        return DB::table('surveys AS s')
                ->select(DB::RAW('s.id, patient_id, m.name, m.clinic, m.enquiry_no, p.registration_no, p.nutritionist, ' . $title . 'comment AS comment, s.created_at'))
                
                ->leftjoin('patient_details AS p',  function($join)
                    {
                        $join->on('s.clinic', '=', 'p.clinic');
                        $join->on('s.registration_no', '=', 'p.registration_no');
                    }) 
                ->leftjoin('marketing_details AS m',  function($join)
                    {
                        $join->on('m.clinic', '=', 'p.clinic');
                        $join->on('m.enquiry_no', '=', 'p.enquiry_no');
                    })
                ->where($title . 'comment', '<>', '')  
                ->whereBetween('s.created_at', array($this->start_date, $this->end_date)) 
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
                //->where('comment', '<>', '')
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
}
