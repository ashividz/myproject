<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Database\QueryException;

use App\Models\Lead;
use App\Models\Patient;
use App\Models\PatientHerb;
use App\Models\Tag;
use App\Models\PatientTag;
use App\Models\PatientNote;
use App\Models\Diet;
use App\Models\PrakritiQuestion;
use App\Models\PatientPrakriti;
use App\Models\Email;
use App\Models\EmailTemplate;
use App\Models\User;

use App\Http\Requests\PatientTagRequest;

use Auth;
use DB;

class PatientController extends Controller
{
    protected $menu;
    protected $daterange;
    protected $start_date;
    protected $end_date;

    public function __construct()
    {
        $this->menu = "patient";
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/m/01 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');
 
    }

    public function viewPrakriti($id)
    {
    	$questions = PrakritiQuestion::get();


        $data = array(
            'menu'      	=> $this->menu,
            'section'  	 	=> 'audit',
            'questions'  	=> $questions,
            'i'         	=>  '1'
        );

        return view('home')->with($data);
    }

    /*public function medical($id)
    {
        $patient = Patient::find($id);

        $data = array(
            'menu'          => $this->menu,
            'section'       => 'partials.medical',
            'patient'       =>  $patient
        );

        return view('home')->with($data);
    }*/

    public function updateHerb($id, Request $request)
    { 
        if(PatientHerb::active($id, $request->state))
        {
            return "Successfully updated";
        }
    }


    public function deleteHerb($id)
    {
        if(PatientHerb::destroy($id))
        {
            return "Successfully deleted";
        }

        return "Error";
    }

    public function tags($id)
    {
        $patient = Patient::find($id);
        $tags = PatientTag::where('patient_id', $id)->get();

        $data = array(
            'menu'          => $this->menu,
            'section'       => 'partials.tag',
            'patient'       =>  $patient,
            'tags'          =>  $tags
        );

        return view('home')->with($data);
    }

    public function saveTag(PatientTagRequest $request, $id)
    {
        try {

            $tag = new PatientTag;

            $tag->patient_id = $id;
            $tag->tag_id = $request->tag;
            $tag->note = $request->note;
            $tag->created_by = Auth::user()->employee->name;
            $tag->save();
            
        } catch (Illuminate\Database\QueryException $e) {
            echo $e;
        }

        return $this->tags($id);
    }

    public function notes($id)
    {
        $patient = Patient::find($id);
        $notes = PatientNote::where('patient_id', $id)
                ->orderBy('id', 'desc')
                ->get();

        $data = array(
            'menu'          => $this->menu,
            'section'       => 'partials.notes',
            'patient'       =>  $patient,
            'notes'          =>  $notes
        );

        return view('home')->with($data);
    }

    public function saveNote(Request $request, $id)
    {
        try {

            $note = new PatientNote;

            $note->patient_id = $id;
            $note->text = $request->note;
            $note->created_by = Auth::user()->employee->name;
            $note->save();
            
        } catch (Illuminate\Database\QueryException $e) {
            echo $e;
        }

        return $this->notes($id);
    }

    

    public function prakriti($id)
    {
        $patient = PatientPrakriti::prakriti($id);

        $questions = PrakritiQuestion::get();

        $data = array(
            'menu'          => 'patient',
            'section'       => 'partials.prakriti',
            'patient'       =>  $patient,
            'questions'     =>  $questions,
            'i'             => '1'
        );

        return view('home')->with($data);   
    }

    public function savePrakriti(Request $request, $id)
    {
        
        $count = PrakritiQuestion::count();
        
        for ($i=1; $i <= $count; $i++) { 
            if(isset($request->answer[$i])) {
                $prakriti = PatientPrakriti::firstOrNew(array('patient_id' => $id, 'question_id' => $i ));
                $prakriti->question_id = $i;
                $prakriti->prakriti_id = $request->answer[$i];
                $prakriti->save();
            }
        }

        //PatientPrakriti::calculatePrakriti($id);

        //dd($request);
        return redirect('patient/'.$id.'/prakriti');   
    }

    public function dispositions($id)
    {

        $patient = Patient::find($id);

        $lead = $patient->lead;

        $dept =  1;
        if (Auth::user()->hasRole('cre') || Auth::user()->hasRole('sales')) {
           $dept =  1;
        }
        elseif (Auth::user()->hasRole('doctor') || Auth::user()->hasRole('nutritionist') || Auth::user()->hasRole('service')) {
           $dept =  2;
        }

        $data = array(
            'menu'          =>  'patient',
            'section'       =>  'partials.dispositions',
            'dept'          =>  $dept,
            'lead'          =>  $lead,
            'patient'       =>  $patient
        );

        return view('home')->with($data);   
    }

    public function details($id)
    {

        $patient = Patient::with('fees', 'diets')->find($id);

        $lead = Lead::with('patient', 'patient.fee', 'patient.fees', 'patient.diets', 'patient.primaryNtr', 'patient.secondaryNtr', 'patient.doctors')
                ->with('status')
                ->find($patient->lead_id);
                
        if (!$lead) {
            echo "Lead does not exist";
            die();
        }

        $data = array(
            'menu'          =>  'patient',
            'section'       =>  'partials.details',
            'lead'          =>  $lead,
            'patient'       =>  $patient
        );

        return view('home')->with($data);   
    }

    public function emails($id)
    {
        $patient = Patient::find($id);

        $lead = $patient->lead;

        $emails = Email::where('lead_id', $lead->id)
                    ->orderBy('created_at', 'DESC')
                    ->limit('20')
                    ->get();

        $templates = EmailTemplate::with('attachments')->get();

        $data = array(
            'menu'          => 'patient',
            'section'       => 'partials.email',
            'lead'          =>  $lead,
            'patient'       =>  $patient,
            'templates'     =>  $templates,
            'emails'        =>  $emails,
            'i'             =>  '1'
        );

        return view('home')->with($data);
    }

    public function advanceDiet(Request $request, $id)
    {
        $patient = Patient::find($id);

        if($patient) {
            $patient->advance_diet = $request->state;
            $patient->save();

            $msg = "Advance diet ";
            $msg .= $request->state ? "set" : "removed";
            $msg .= " for ".$patient->lead->name;
            
            return $msg;
        } 

        return "Patient not found";
    }

    public function get(Request $request)
    {
        return Patient::with('suit')
                ->find($request->id);
    }


    public function calls($id)
    {
        $serviceUsers  = User::getUsersByRole('service');
        $doctors       = User::getUsersByRole('doctor');
        $serviceTLs    = User::getUsersByRole('service_tl');
        $nutritionists = User::getUsersByRole('nutritionist');

        $serviceIds = array_merge(
                $serviceUsers->pluck('id')->toArray(),
                $doctors->pluck('id')->toArray(),
                $serviceTLs->pluck('id')->toArray(),
                $nutritionists->pluck('id')->toArray()
            );
        $serviceUserNames   = User::whereIn('id',$serviceIds)->get()->pluck('username')->toArray();

        $serviceNames       = User::whereIn('id',$serviceIds)->with('employee')->get()->pluck('employee.name')->toArray();

        $patient = Patient::findOrFail($id);

        $lead    = Lead::with(['dialerphonedispositions' => function($query) use($serviceUserNames){
                    $query->with('user')
                    ->whereIn('username',$serviceUserNames)
                    ->orderBy('eventdate','desc')
                    ->limit(20);
                }])            
                ->with(['dialermobiledispositions' => function($query) use($serviceUserNames){
                    $query->with('user')
                    ->whereIn('username',$serviceUserNames)
                    ->orderBy('eventdate','desc')
                    ->limit(20);;
                }])
                ->with(['dispositions'=> function($query) use($serviceNames) {
                    $query->whereIn('name',$serviceNames)
                    ->orderBy('created_at','desc')
                    ->limit(20);;
                }])
                ->with('patient')
                ->findOrFail($patient->lead_id);

        $dialer_dispositions = collect();
        foreach($lead->dialerphonedispositions as $d)
            $dialer_dispositions->push($d);
        foreach($lead->dialermobiledispositions as $d)
            $dialer_dispositions->push($d);        
        $dialer_dispositions =  $dialer_dispositions->sortByDesc('eventdate')->unique()->take(20);
        
        $data = array(
            'menu'               =>  'lead',
            'section'            =>  'modals.dispositions',
            'lead'               =>  $lead,
            'dialer_dispositions'=>  $dialer_dispositions,
            'i'                  =>  '0'
        );

        return view('lead.modals.dispositions')->with($data);
    }

}