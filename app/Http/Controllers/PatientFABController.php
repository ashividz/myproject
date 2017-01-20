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
use App\Models\PatientEatingtip;
use App\Models\PatientMeasurement;
use App\Models\PatientWeight;
use App\Models\PatientSymptom;
use App\Models\FabDiet;
use App\Models\FabGuideline;
use App\Models\PatientFab;
use App\Http\Requests\PatientTagRequest;

use Auth;
use DB;
use Carbon;
use Mail;
use PDF;
use View;
class PatientFABController extends Controller
{
    protected $menu;
    protected $daterange;
    protected $start_date;
    protected $end_date;

    public function __construct(Request $request)
    {
        $this->menu = "patient";
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/m/01 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');
        $this->nutritionist = isset($request->user) ? $request->user:'';
    }

    public function index(Request $request)
    {
         $data = array(
            'menu'          => $this->menu,
            'section'       => 'partials.fab',
            'i'             =>  '1'
        );

        return view('home')->with($data);
    }
    
     public function getSymptoms(Request $request)
    {    
        $patient = Patient::find($request->id);
         $data = array(
            'menu'          => $this->menu,
            'patient'       => $patient,
            'section'       => 'partials.symptoms',
            'i'             =>  '1'
        );

        return view('home')->with($data);
    }
    
    public function getSentFab($id)
    {        
       
        $fab = PatientFab::find($id);
       
        return $fab->content;
       /*  $data = array(
            'categories'    =>  $categories,
            'cart'          =>  $cart,
        );
       return view('cart.modals.products')->with($data);*/
    }

    public function fabReport(Request $request)
    {
        $patients = Patient::with('lead.cre', 'fees')->wherehas('fees', function($q){
                                    $q->where('end_date', '>', date('Y-m-d'));
                                }, '<', 1)
                    ->join(DB::raw("(SELECT * FROM fees_details A WHERE end_date = (SELECT MAX(end_date) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS c"), function($join) {
                             $join->on('patient_details.id', '=', 'c.patient_id');
                        })->select('patient_details.*', 'c.id as fid', 'c.start_date as start_date', 'c.end_date as end_date');;

        if(isset($request->user) && !empty($request->user) && $request->user != 'Select User')
            $patients = $patients->where('nutritionist', $request->user);

        $patients = $patients->has('fab','<', 1)->orderBy('c.end_date', 'desc')->limit(100)->get();

        $patientsFab = Patient::with('lead.cre', 'fab', 'fees')->wherehas('fees', function($q){
                                    $q->where('end_date', '>', date('Y-m-d'));
                                }, '<', 1)
                    ->join(DB::raw("(SELECT * FROM patient_fab A WHERE id = (SELECT MAX(id) FROM patient_fab B WHERE A.patient_id=B.patient_id)) AS c"), function($join) {
                             $join->on('patient_details.id', '=', 'c.patient_id');
                        })->select('patient_details.*', 'c.id as fid', 'c.content as content', 'c.created_at as fab_date');
        if(isset($request->user) && !empty($request->user) && $request->user != 'Select User')
            $patientsFab = $patientsFab->where('nutritionist', $request->user);

        $patientsFab = $patientsFab->has('fab','>', 0)->orderBy('c.id', 'desc')->limit(100)->get();

        $users = User::getUsersByRole('nutritionist');
        
        $data = array(            
        'menu'              =>  'patient',
        'section'           =>  'patients_fab_report',
        'users'             =>  $users,
        'name'              =>  $this->nutritionist,
        'patients'          =>  $patients,
        'patientsFab' =>  $patientsFab,
        'x'                 =>  '1',
        'y'                 =>  '1',
        'z'                 =>  '1'
    );
    return view('home')->with($data);
       
    }

    public function editMeasurements(Request $request)
    {
        //$measurements = PatientMeasurement::where('patient_id', $request->id)->orderBy('id')->first();
        $measurements = PatientMeasurement::firstOrNew(array('patient_id' => $request->id));
        //$measurements->height = $request->initialHeight;
        $measurements->arms = $request->initialArm;
        $measurements->chest = $request->initialChest;
        $measurements->waist = $request->initialWaist;
        $measurements->abdomen = $request->initialAbdomen;
        $measurements->hips = $request->initialHips;
        $measurements->thighs = $request->initialThighs;
        $measurements->save();

        $lastMeasurement = new PatientMeasurement;
        //$measurements->height = $request->lastHeight;
        $lastMeasurement->patient_id = $request->id;
        $lastMeasurement->arms = $request->lastArm;
        $lastMeasurement->chest = $request->lastChest;
        $lastMeasurement->waist = $request->lastWaist;
        $lastMeasurement->abdomen = $request->lastAbdomen;
        $lastMeasurement->hips = $request->lastHips;
        $lastMeasurement->thighs = $request->lastThighs;
        $lastMeasurement->created_by = Auth::user()->id;
        $lastMeasurement->save();

        $patient = PatientWeight::firstOrNew(array('patient_id' => $request->id));
        $patient->weight = $request->initialWeight;
        $patient->date = date('Y-m-d');
        $patient->created_by = Auth::user()->id;
        $patient->save();

        $patient = new PatientWeight;
        $patient->patient_id = $request->id;
        $patient->weight = $request->lastWeight;
        $patient->date = date('Y-m-d');
        $patient->created_by = Auth::user()->id;
        $patient->save();
        return $patient->id;

    }

    public function editSymptoms(Request $request)
    {           
        $initialSymptom = PatientSymptom::firstOrNew(array('patient_id' => $request->id));
        $initialSymptom->energy_level = $request->initial_energy_level;
        //$initialSymptom->skin = $request->initial_skin;
        $initialSymptom->constipation = $request->initial_constipation;
        $initialSymptom->gas = $request->initial_gas;
        $initialSymptom->acidity = $request->initial_acidity;
        $initialSymptom->water_retention = $request->initial_water_retention;
        $initialSymptom->joint_pain = $request->initial_joint_pain;
        $initialSymptom->emotional_eating = $request->initial_emotional_eating;
        $initialSymptom->sugar_food_craving = $request->initial_sugar_food_craving;
        $initialSymptom->headache = $request->initial_headache;
        $initialSymptom->backache = $request->initial_backache;
        $initialSymptom->general_feeling = $request->initial_general_feeling;
        $initialSymptom->created_by = Auth::user()->id;
        $initialSymptom->save();

        $lastSymptom = new PatientSymptom;
        $lastSymptom->patient_id = $request->id;
        $lastSymptom->energy_level = $request->last_energy_level;
        //$lastSymptom->skin = $request->last_skin;
        $lastSymptom->constipation = $request->last_constipation;
        $lastSymptom->gas = $request->last_gas;
        $lastSymptom->acidity = $request->last_acidity;
        $lastSymptom->water_retention = $request->last_water_retention;
        $lastSymptom->joint_pain = $request->last_joint_pain;
        $lastSymptom->emotional_eating = $request->last_emotional_eating;
        $lastSymptom->sugar_food_craving = $request->last_sugar_food_craving;
        $lastSymptom->headache = $request->last_headache;
        $lastSymptom->backache = $request->last_backache;
        $lastSymptom->general_feeling = $request->last_general_feeling;
        $lastSymptom->created_by = Auth::user()->id;
        $lastSymptom->save();
        return $initialSymptom->id;
    }

     public function saveFABDiet(Request $request)
    {
        $date = date('Y-m-d', strtotime($request->diet_date));

        $diet = FabDiet::where('patient_id', $request->id)
                    ->where('date_assign', $date)
                    ->first();
        if(!$diet)
        {
           $diet = new FabDiet;
        }

        $patient = Patient::find($request->id);
        $diet->patient_id = $request->id;
        $diet->nutritionist = Auth::user()->employee->name;
        $diet->date = date('Y-m-d h:i:s');
        $diet->date_assign = $request->diet_date;
        $diet->weight = trim($request->weight);
        //$diet->early_morning = trim($request->early_morning);
        $diet->breakfast = trim($request->breakfast);
        $diet->mid_morning = trim($request->mid_morning);
        $diet->lunch = trim($request->lunch);
        $diet->evening = trim($request->evening);
        $diet->dinner = trim($request->dinner);
        $diet->herbs = $this->getHerbs($patient->herbs);
        $diet->rem_dev = trim($request->rem_dev);
        $diet->save();
        return $diet->id;
    }

     public function getFabDiet(Request $request)
    {

        $diet = FabDiet::where('patient_id', $request->patient_id)
                    ->where('date_assign', $request->diet_date)
                    ->first();
        //dd($diet);
        return $diet;
    }

     public function editEatingTip(Request $request)
    {
        $eatingTip = PatientEatingTip::find($request->id);
        $eatingTip->name = $request->name;
        $eatingTip->save();
        return $eatingTip->id;
    }

    public function getFabData(Request $request)
    {

        $patient = Patient::with('fee', 'lead', 'medicals', 'symptoms', 'measurements', 'weights', 'suit', 'blood_type', 'rh_factor')
                            ->with('herbs.herb')->find($request->patient_id);
       
        $initialWeight   = $patient->weights->sortBy('id')->first();
        $lastWeight  = $patient->weights->sortByDesc('id')->first();

        $initialSymptom   = $patient->symptoms->sortBy('id')->first();
        $lastSymptom  = $patient->symptoms->sortByDesc('id')->first();
        $patient->initialSymptom = $initialSymptom;
        $patient->lastSymptom = $lastSymptom;

        $patient->initialWeight   = $patient->weights->sortBy('id')->first();
        $patient->lastWeight  = $patient->weights->sortByDesc('id')->first();

        $patient->initialMeasurement   = $patient->measurements->sortBy('id')->first();
        $patient->lastMeasurement  = $patient->measurements->sortByDesc('id')->first();

        $patient->initialMedical   = $patient->medicals->sortBy('id')->first();
        $patient->lastMedical  = $patient->medicals->sortByDesc('id')->first();

        $patient->lastFee  = $patient->fees->sortByDesc('id')->first();

        $weightDiff = $lastWeight->weight - $initialWeight->weight;
        $weightDiff = round($weightDiff, 2);
        if($weightDiff>0)
            $weightDiff .= " kg GAINED ";
        else if($weightDiff<0)
        {
            $weightDiff *= -1;
            $weightDiff .= " kg LOST";
        }
        else
            $weightDiff = "No Weight Gain";
        $patient->weightDiff = $weightDiff;
        $herb_names = $this->getHerbs($patient->herbs);
        $patient->herb_names = Diet::nl2list($herb_names);
        $prakriti = PatientPrakriti::prakriti($request->patient_id);
        $patient->prakriti = $prakriti;
        $fee = $patient->fees->sortByDesc('end_date')->first();
        $diet_dates = [];
        for($i=1; $i<=7; $i++)
            $diet_dates[] = date('Y-m-d', strtotime($fee->end_date.' +'.$i.' days'));
        $patient->diet_dates = $diet_dates;

        return $patient;
    }

    public function previewMail(Request $request)
    {
        $request->patient_id  = $request->id;  
        $patient = $this->getFabData($request);
        $guidelines = FabGuideline::get();
        $patient->guidelines = $guidelines;
        $diets = FabDiet::where('patient_id',$request->patient_id)->orderBy('date_assign')->get();
        $dietbody = "<table cellspacing='0' cellpadding='10' style='padding: 10px'";

        $dietbody .= "<tr><td colspan='2' style='background: #ddd;padding: 15px'><h3 style='margin: 0px'> 7 DAYS COMPLEMENTARY DIET PLAN</h3></td></tr>";
        foreach ($diets as $diet) {
                $dietbody .= Diet::emailBody($diet);
            }
        $dietbody .= "</table>";
        $eatingTips = PatientEatingtip::where('patient_id', $request->patient_id)->get();

        
         $data = array(
            'menu'          => 'patient.fab_email',
            'patient'       => $patient,
            'section'       => '',
            'eatingTips'    => $eatingTips,
            'dietbody'      =>  $dietbody,
            'i'             =>  '1'
        );

        return view('patient.fab_email')->with($data);

    }

    public function sendFabMail(Request $request)
    {   
        $patientFab = null;
        $request->patient_id  = $request->id;  
        $patient = $this->getFabData($request);
        $guidelines = FabGuideline::get();
        $patient->guidelines = $guidelines;
        $diets = FabDiet::where('patient_id',$request->patient_id)->orderBy('date_assign')->get();
        $dietbody = "<table cellspacing='0' cellpadding='10' style='padding: 10px'";

        $dietbody .= "<tr><td colspan='2' style='background: #ddd;padding: 15px'><h3 style='margin: 0px'> 7 DAYS COMPLEMENTRY DIET PLAN</h3></td></tr>";
        $eatingTips = PatientEatingtip::where('patient_id', $request->patient_id)->get();

        if (trim($patient->lead->email) <> '') {
              foreach ($diets as $diet) {
                $dietbody .= Diet::emailBody($diet);
            }  
            $dietbody .= "</table>";              
            //dd($body);
           // $pdf = PDF::loadView('patient.fab_email', ['patient' => $patient, 'eatingTips' => $eatingTips, 'dietbody' => $dietbody]);
           // $filename = "FAB_Report-".$request->id.".pdf";
           // $pdf->save($filename);

            $view = View::make('patient.fab_email', ['patient' => $patient, 'eatingTips' => $eatingTips, 'dietbody' => $dietbody]);
            if($view)
            {
                $fabcontent = $view->render();
                $patientFab = new PatientFab;
                $patientFab->patient_id = $request->id;
                $patientFab->content = $fabcontent;
                $patientFab->created_by = Auth::user()->id;
                $patientFab->save();
            }
            if($patientFab)
            {
              Mail::send('patient.fab_email', ['patient' => $patient, 'eatingTips' => $eatingTips, 'dietbody' => $dietbody], function($message) use ($patient)
                    {
                        $message->to($patient->lead->email, $patient->lead->name)
                            //->bcc("diet@nutrihealthsystems.co.in")
                            ->bcc("diet@nutrihealthsystems.co.in")
                            ->subject("FINAL ANALYSIS BROCHURE - ".$patient->lead->name." - ".date('D, jS M, Y H:i:s'))
                            ->from('diet@nutrihealthsystems.co.in', 'Nutri-Health Systems');
                            
                        if (trim($patient->lead->email_alt) <> '') {
                            $message->cc($patient->lead->email_alt, $patient->lead->name);
                        }
                    });
            }
        }
        else {
            $message .= '<li>Email does not exist for '.$patient->lead->name.'</li>';
            $status = 'error';
        }
        return $patientFab;

    }

    public function weightUpdate(Request $request)
    {
        $patient = PatientWeight::where('patient_id', $request->patient_id)->orderBy('id')->get();
        $initial_weight = $request->initial_weight;
        $final_weight = $request->final_weight;
        $patientIntinial = $patient->first();
        $patientIntinial->weight = $initial_weight;
        $patientIntinial->created_by = Auth::user()->id;
        $patientIntinial->save();

        $patientFinal = $patient->last();
        if($patient->count() == 1)
        {
            $patientFinal = new PatientWeight;
            $patientFinal->date = date('Y-m-d');
        }
        $patientFinal->weight = $final_weight;
        $patientFinal->created_by = Auth::user()->id;
        $patientFinal->save();
        return $patient;
    }

    private function getHerbs($herbs)
    {
        $herb_names = '';
        if ($herbs) {
            foreach ($herbs as $herb) {
                $when = '';
                $herb_names .= $herb->herb->name." : ".$herb->quantity." ";
                $herb_names .= $herb->unit?$herb->unit->name:"";
                $herb_names .= " ".$herb->remark;
                foreach ($herb->mealtimes as $mealtime) {
                        $when .= $mealtime->mealtime ? $mealtime->mealtime->name . ' & ' : '' ;
                    }
                $when = rtrim($when, "& ");
                $herb_names .= ' ('.$when.') '; 
                $herb_names .= " \n ";
            }
        }
        return $herb_names;
    }

    public function patientFab($id)
    {
        $patient = Patient::find($id);
       
         $data = array(
            'menu'          => $this->menu,
            'patient'       =>  $patient,
            'section'       => 'partials.fab',
            'i'             =>  '1'
        );

        return view('home')->with($data);
    }
    
    public function deleteEatingTip(Request $request)
    {  
        $status = PatientEatingtip::withTrashed()->find($request->id);
        $status->deleted_at = $status->deleted_at ? null : Carbon::now();
        $request->patient_id = $status->patient_id;
        $status->save();
        return $this->getAllEatingTips($request);
    }

    public function getAllEatingTips(Request $request)
    {  
        return PatientEatingtip::where('patient_id', $request->patient_id)->get();
    }

    public function addEatingTip(Request $request)
    {
        $eatingtip = new PatientEatingtip;
        $eatingtip->name = $request->name;
        $eatingtip->patient_id = $request->patient_id;
        $eatingtip->save();
        //dd($request);
        return $this->getAllEatingTips($request);
    }

}