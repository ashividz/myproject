<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Lead;
use App\Models\Leads;
use App\Models\Disposition;
use App\Models\User;
use App\Models\Status;
use App\Models\Source;
use App\Models\Channel;
use App\Models\Patient;
use App\Models\Fee;
use App\Models\CreSurveyQuestion;
use App\Models\CallDisposition;
use App\Models\CrePatientSurvey;
use App\Models\CrePatientSurveyAnswer;
use Auth;
use DB;
use Carbon;

class CREController extends Controller
{
    protected $url = "/lead/";
    protected $cre;
    protected $daterange;
    protected $start_date;
    protected $end_date;
    protected $menu = "cre";

    public function __construct(Request $request)
    {
    
        $this->cre = isset($request->user) ? $request->user : Auth::user()->employee->name;
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y-m-d 0:0:0", strtotime("-45 days"));
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y-m-d 23:59:59');
        
    } 
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::getUsersByRole('cre');

        $statuses = Status::get();

        $leads = Lead::select('marketing_details.*')
                    ->join(DB::raw('(SELECT * FROM lead_cre A WHERE id = (SELECT MAX(id) FROM lead_cre B WHERE A.lead_id=B.lead_id)) AS c'), function($join) {
                        $join->on('marketing_details.id', '=', 'c.lead_id');
                    })
                    ->whereBetween('c.created_at', array(date('y-m-01'), date('Y-m-d h:i:s') ))
                    ->where('c.cre', $this->cre)//$this->cre
                    ->get();

        $statuses->leads = $leads->count();

        foreach ($statuses as $status) {
            $status->count = $leads->where('status_id', $status->id)->count();
        }

        

        //dd($statuses);

        $data = array(
            'section'           =>  'index',
            'menu'              =>  $this->menu,
            'users'             =>  $users,
            'name'              =>  $this->cre,
            'statuses'          =>  $statuses
        );
        return view('home')->with($data);
    }

    /**
     * Show the Leads for a CRE.
     *
     * @return Response
     */
    public function viewLeads(Request $request, $id = null)
    {
        if($id) {
            $this->cre = User::find($id)->employee->name;
        }
        
        $leads = Lead::getLeadsByUser($this->cre, $this->start_date, $this->end_date);
        
        //dd($leads);
        //$section = "leads";

        $users = User::getUsersByRole('cre');

        $data = array(
            'menu'          => $this->menu,
            'section'       => "leads",
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'url'           => $this->url,
            'leads'         => $leads,
            'users'         => $users,
            'name'          => $this->cre,
            'i'             =>  1
        );

        return view('home')->with($data);
    }

    /**
     * Display Pipelines
     *
     * @return Response
     */
    public function showPipelines()
    {
        //Update created_at field in Fee table
        //DB::update("INSERT INTO lead_status (lead_id, clinic, enquiry_no, status, created_at) SELECT p.lead_id, p.clinic, p.enquiry_no, 5, f.entry_date FROM patient_details p JOIN fees_details f ON f.clinic=p.clinic AND f.registration_no=p.registration_no LEFT JOIN (SELECT clinic, enquiry_no, status FROM lead_status A WHERE id = (SELECT  MAX(id) FROM lead_status B WHERE A.clinic=B.clinic AND A.enquiry_no=B.enquiry_no)) s ON s.clinic=p.clinic AND s.enquiry_no=p.enquiry_no WHERE f.entry_date >= DATE(DATE_SUB(f.end_date, INTERVAL 7 DAY)) AND status NOT IN(5,6)");
        $cre = $this->cre;
        $start_date = $this->start_date;
        $end_date = $this->end_date;

        $calls = Disposition::where('parent_id', 0)
                            ->get();

        foreach ($calls as $call) {
            $call->dispositions = Disposition::where('parent_id', $call->id)
                                            ->whereIn('dept', [0,1])
                                            ->get();
        }
            
        $statuses = Status::get();


        $leads = Lead::whereHas('cre', function($q) {
                        $q->whereBetween('created_at', array($this->start_date, $this->end_date));
                    })
                    ->with(['disposition'=> function($q) use($cre){
                        $q->where('name', $cre);
                    }])
                    ->where('cre_name', $cre)
                    ->orderBy('updated_at', 'desc')
                    ->get();

        $statuses->leads = $leads->count();


        foreach ($statuses as $status) {
            $status->leads = $leads->where('status_id', $status->id);
        }



        $users = User::getUsersByRole('cre');

        $data = array(
            'menu'              =>  $this->menu,
            'section'           =>  'pipelines',
            'start_date'        =>  $this->start_date,
            'end_date'          =>  $this->end_date,
            'users'             =>  $users,
            'name'              =>  $this->cre,
            'calls'             =>  $calls,
            'statuses'          =>  $statuses
        );        

        return view('home')->with($data);
    }


    /**
     * Display intrested Leads
     *cre/leads
     * @return Response
     */

    public function interested()
    {
        $cre = $this->cre;
        $start_date = $this->start_date;
        $end_date = $this->end_date;




        $active = CallDisposition::where('disposition_id', '9')
        ->whereHas('lead.patient.fees', function($q) {
            $q->where('end_date', '>=', Carbon::today())
             ->where('total_amount', '>' , 0);
        })
        ->whereBetween('created_at', array($this->start_date, $this->end_date))
        ->where('name' , $cre)
        ->get();
        
    
        $arr = [];
        foreach($active as $act)
        {
            $arr[] = $act->lead->id;
        }
        

        $leads = CallDisposition::where('disposition_id' , '9')
        ->whereHas('lead' , function($q) use($arr){
            $q->whereNotIn('id' , $arr);
        })
        ->whereBetween('created_at', array($this->start_date, $this->end_date))
        ->where('name' , $cre)
        ->groupBy('lead_id')
        ->get();

        $leadId = [];
        foreach ($leads as $l) {
            $leadId[] = $l->lead->id;
        }

    

        $leads = Lead::has('disposition')
        ->where('cre_name' , $cre)
        ->whereIn('id' , $leadId )
        ->get();
       
       
       

        $users = User::getUsersByRole('cre');
        $data = array(
            'menu'              =>  $this->menu,
            'section'           =>  'interested',
            'start_date'        =>  $this->start_date,
            'end_date'          =>  $this->end_date,
            'users'             =>  $users,
            'name'              =>  $this->cre,
            'leads'             =>  $leads
        ); 

        return view('home')->with($data);

    }

    /**
     * Cre active client so that they can call them on every 15th day for feedback.
     *
     * @return Response
     */

    public function creActiveClient()
    {
        $cre = $this->cre;
        $patients = Lead::has('patient.cfee')
                    ->with('patient.cfee')
                    ->where('cre_name' , $cre)
                    ->get();

        $users = User::getUsersByRole('cre');

       // return $patients;

        $data = array(
            'menu'              =>  $this->menu,
            'section'           =>  'activeClient',
            'start_date'        =>  $this->start_date,
            'end_date'          =>  $this->end_date,
            'users'             =>  $users,
            'name'              =>  $this->cre,
            'patients'          =>  $patients
        );
         return view('home')->with($data);
    }

    public function survey($id)
    {
        $patient = Patient::find($id);
        $questions = CreSurveyQuestion::get();
        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'survey',
            'patient'       =>  $patient,
            'questions'     =>  $questions
        );

        return view('home')->with($data);
    }

    public function saveCreSurvey(Request $request)
    {
        $survey = CrePatientSurvey::saveSurvey($request);

        
            $size = count($request->comment);
            
            for ($i=1; $i <= $size; $i++) { 
                if(!empty($request->answer[$i]))
                {
                    $answer = CrePatientSurveyAnswer::saveAnswer($survey->id, $i, $request->answer[$i], $request->comment[$i]);
                    
                }                    
            }
          return redirect('cre/activeClient');
    }


    public function creSurvey()
    {
        $questions = CreSurveyQuestion::with('answers')->get();

        

        foreach ($questions as $question) {
            $answers = CrePatientSurveyAnswer::with('answer')
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
            'menu'          =>  'sales',
            'section'       =>  'cresurvey',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'questions'     =>  $questions
        );

        return view('home')->with($data);
    }

    /**
     * Display Dispositions
     *
     * @return Response
     */
    public function showCallDispositions()
    {

        
        $dispositions = CallDispositions::filterByUser($this->cre, $this->start_date, $this->end_date);
         
        $section = "dispositions";

        $data = array(
            'url'           => $this->url,
            'menu'          => $this->menu,
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'section'       => $section,
            'dispositions'  => $dispositions
        );

        return view('home')->with($data);
    }

    public function viewCallbacks()
    {
        $data   =   array(
            'menu'      =>  $this->menu,
            'section'   =>  'callback'
        );

        return view('home')->with($data);
    }   

    public function viewChannelPerformance()
    {
        $users = User::getUsersByRole('cre');

        $channels = Channel::with('sources')->get();

        foreach ($channels as $channel) {
            $sources = array();
            foreach ($channel->sources as $source) {
                $sources[] = $source->id;
            }
                
            $channel->leads = Lead::leftJoin('lead_cre AS c','marketing_details.id', '=', 'c.lead_id')
                    ->where('cre', $this->cre)
                    ->whereIn('source_id', $sources)
                    ->whereBetween('c.created_at', array($this->start_date, $this->end_date))
                    ->count();

            $patients = Patient::select('patient_details.*', 'total_amount')
                        ->join('fees_details AS f', 'f.patient_id', '=', 'patient_details.id') 
                        ->where('cre', $this->cre)
                        ->whereIn('source_id', $sources) 
                        ->whereBetween('f.entry_date', array($this->start_date, $this->end_date))
                        ->get();

            $channel->patients = $patients->count();

            $channel->amount = $patients->sum('total_amount');

        }

        $data   =   array(
            'menu'          =>  $this->menu,
            'section'       =>  'channel',
            'channels'      =>  $channels,
            'users'         =>  $users,
            'name'          =>  $this->cre,
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date
        );

        return view('home')->with($data);
    }

    public function viewCountryPerformance()
    {
        $users = User::getUsersByRole('cre');

        $patients = Patient::join('marketing_details AS m', 'patient_details.lead_id', '=', 'm.id')
                ->join('fees_details AS f', 'f.patient_id', '=', 'patient_details.id')
                ->leftJoin('yuwow_alpha_1_0.countries AS c', 'c.country_code', '=', 'm.country')
                ->leftJoin('yuwow_alpha_1_0.region_codes AS r', 'r.region_code', '=', 'm.state')
                ->whereBetween('f.entry_date', array($this->start_date, $this->end_date))
                ->where('f.cre', $this->cre)
                ->select('country_name', 'region_name', DB::RAW('SUM(total_amount) AS amount, COUNT(*) AS cnt'))
                ->groupBy('country', 'state')
                ->orderBy('cnt', 'desc')
                ->get();

        //dd($patients);        

        $data   =   array(
            'menu'          =>  $this->menu,
            'section'       =>  'country',
            'users'         =>  $users,
            'name'          =>  $this->cre,
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'patients'      =>  $patients
        );

        return view('home')->with($data);
    }

    public function viewProgramEndList(Request $request) 
    {
        $patients = Patient::viewProgramEndList($this->cre);

        $users = User::getUsersByRole('cre');

        //dd($patients);  
        $data   =   array(
            'menu'          =>  $this->menu,
            'section'       =>  'program_end',
            'patients'      =>  $patients,
            'users'         =>  $users,
            'name'           =>  $this->cre
        );

        return view('home')->with($data);
    }

    public function payments()
    {
        $users = User::getUsersByRole('cre');

        $fees = Fee::with('patient', 'patient.lead')
                    ->where('cre', $this->cre)
                    ->whereBetween('entry_date', array($this->start_date, $this->end_date))
                    ->get();

        $amount = Fee::where('cre', $this->cre)
                    ->whereBetween('entry_date', array($this->start_date, $this->end_date))
                    ->sum('total_amount');

        $data   =   array(
            'menu'          =>  $this->menu,
            'section'       =>  'payment',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'users'         =>  $users,
            'name'          =>  $this->cre,
            'fees'          =>  $fees,
            'amount'        =>  $amount
        );

        return view('home')->with($data);
    }

    //View Dead Leads

    
}
