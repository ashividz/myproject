<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Patient;
use App\Models\PatientWeight;
use App\Models\Lead;
use App\Models\Nutritionist;
use App\Models\CallDisposition;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Email;
use App\Models\EmailTemplate;
use App\Models\Diet;
use App\Models\MasterDietCopy;
use App\Support\SMS;
use App\Models\BloodGroup;
use App\Models\RhFactor;
use App\Models\Program;
use App\Models\Prakriti;
use App\Models\Master_Diet;
use App\Models\NutritionistLeave;

use DB;
use Auth;
use Mail;

class ServiceController extends Controller
{
    protected $menu;
    protected $date;
    protected $daterange;
    protected $start_date;
    protected $end_date;
    protected $nutritionist;

    public function __construct(Request $request)
    {
        $this->menu = "service";
        $this->nutritionist = isset($request->user) ? $request->user : Auth::user()->employee->name;
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/m/d 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');
        $this->date = isset($_POST['date']) ? date('Y-m-d', strtotime($_POST['date'])) : date("Y-m-d");
 
    }   

    public function index()
    {
        $data = array(
            'menu'      => $this->menu,
            'section'   => 'dashboard'
        );

        return view('home')->with($data);
    }
    
    public function viewAssginedNutritionist()
    {
        $patients = Patient::getActivePatients();

        //dd($patients);

        $data = array(
            'menu'      => $this->menu,
            'section'   => 'assign_nutritionist',
            'patients'  => $patients
        );

        return view('home')->with($data);
    }

    public function saveNutritionist(Request $request)
    {
        /** Removed because of request from Service team
            Saaz Rai - 26th Nov 2016
        **/
        /*if (Nutritionist::ifMultipleNtrOnSameDate($request)) {
            return "Cannot add multiple Nutritionists on same date";
        }*/

        if (Nutritionist::ifSameNutritionist($request)) {
            return "Cannot add same Nutritionist";
        }

        if (Nutritionist::ifSamePrimarySecondaryNutritionist($request)) {
            return "Primary & Secondary Nutritionist cannot be same";
        }


        if(Nutritionist::assignNutritionist($request))
        {
            Nutritionist::updateNutritionist($request);
            return $request->value;
        }
        return "Error";
    }

    public function saveDoctor(Request $request)
    {
        if (Doctor::ifSameDoctor($request)) {
            return "Cannot add same Doctor";
        }

        if(Doctor::assignDoctor($request))
        {
            Doctor::updateDoctor($request);
            return $request->value;
        }
        return "Error";
    }

    public function audit()
    {
        $patients = Patient::select('patient_details.*', 'l.name', 'l.dob', 'l.email', 'l.phone', 'm.date AS medical_date', 'pm.created_at AS measurement_date', 'f.entry_date AS fee_date', 'f.start_date', 'f.end_date')
                    ->leftJoin(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                        $join->on('patient_details.id', '=', 'f.patient_id');
                    })
                    ->leftJoin(DB::raw('(SELECT * FROM patient_measurements A WHERE id = (SELECT MAX(id) FROM patient_measurements B WHERE A.patient_id=B.patient_id)) AS pm'), function($join) {
                        $join->on('patient_details.id', '=', 'pm.patient_id');
                    })
                    ->leftJoin(DB::raw('(SELECT * FROM medical A WHERE id = (SELECT MAX(id) FROM medical B WHERE A.patient_id=B.patient_id)) AS m'), function($join) {
                        $join->on('patient_details.id', '=', 'm.patient_id');
                    }) 
                    ->leftJoin('marketing_details AS l', 'l.id', '=', 'patient_details.lead_id')
                    ->where('f.end_date', '>=', DB::RAW('CURDATE()'))
                    ->orderBy('f.end_date', 'DESC')
                    //->limit(env('DB_LIMIT'))
                    ->get();

        $data = array(
            'menu'      => $this->menu,
            'section'   => 'audit',
            'patients'  => $patients,
            'i'         =>  '1'
        );

        return view('home')->with($data);
    }

    /*
        Bulk Email & SMS From 
    */
    public function bulk()
    {
        $users = User::getUsersByRole('nutritionist');

        $patients = Patient::getActivePatients($this->nutritionist);

        $templates = EmailTemplate::where('email_template_category_id',1)->get();

        $data = array(
            'menu'      => 'service',
            'section'   => 'bulk',
            'users'     =>  $users,
            'name'      =>  $this->nutritionist,
            'patients'  =>  $patients,
            'templates' =>  $templates,
            'i'         =>  '1'
        );

        return view('home')->with($data);   
    }

    /*
        Send Bulk SMS & Email From 
    */
    public function sendBulk(Request $request)
    {
        //dd($request);
        if($request->email) {
            $leads = Lead::whereIn('id', $request->email)->get();
        } elseif ($request->sms) {
            $leads = Lead::whereIn('id', $request->sms)->get();
        } else {
            return "Unable to proceed";
        }
            

        //dd($leads);

        $template = EmailTemplate::find($request->template_id);
        
        $subject = $template->subject;
        $from = $template->from;
        //dd($template);

        foreach ($leads as $lead) {
            //echo $lead->email."<p>";
            $email = new Email;
            $email->user_id = Auth::user()->id;
            $email->lead_id = $lead->id;
            $email->template_id = $request->template_id;                   
                    

            /* Send Email */
            if($request->email) {
                if (in_array($lead->id, $request->email)) {
                    $body = $template->email;
                    $body = str_replace('$customer', $lead->name, $body);

                    if($lead->patient){
                        $body = str_replace('$nutritionist', $lead->patient->nutritionist, $body);
                    }

                    Mail::send('templates.emails.empty', array('body' => $body), function($message) use ($lead, $subject, $from)
                    {
                       $message->to($lead->email, $lead->name)
                            ->bcc("diet@nutrihealthsystems.co.in")
                            ->subject($subject)
                            ->from($from, 'Nutri-Health Systems');
                        
                        //Add CC
                        if (trim($lead->email_alt) <> '') {
                            $message->cc($lead->email_alt, $lead->name);
                        }
                    });

                    $email->email = $body;
                    $email->save();

                }    
            }      
            
            /* Send SMS */
            if($request->sms) {
                if (in_array($lead->id, $request->sms) && $template->sms <> '' && $lead->mobile <> '') {
                    $sms = new SMS;

                    $email->sms_response = $sms->send($lead->mobile, $template->sms);
                    $email->save();
                }            
            }
        }
        return "Successfully sent!";
    }

    public function diets()
    {
        $diets = Diet::with('patient.suit')
                    ->whereBetween('date', array($this->start_date, $this->end_date))
                    ->limit(env('DB_LIMIT'))
                    ->get();
        
        $data = array(
            'menu'          => 'service',
            'section'       => 'diets',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'diets'         =>  $diets,
            'i'             =>  '1'
        );

        return view('home')->with($data);   
    }

    public function sendDiets(Request $request)
    {
        $status = '';
        $sms = '';

        $diets = Diet::whereIn('id',$request->check)->get();

        //dd($diets);

        if($request->sms)
        {
            foreach ($diets as $diet) {
                
                $patient = Patient::find($diet->patient_id);

                if ($patient->lead->country == 'IN') {
                    $sms = Diet::sendSms($diet); 
                    $status .= '<li>Diet SMS Sent to '.$patient->lead->name.' ('.$sms.')</li>';
                }
                
            }     
        }

        foreach ($diets as $diet) {

            $patient = Patient::find($diet->patient_id);

            $body = '';

            $body .= Diet::emailHeader($patient);
            $body .= Diet::emailBody($diet);
            $body .= Diet::emailFooter();

            if(Diet::email($patient, $body)) {
                $status .= '<li>Diet Sent to '.$patient->lead->name.'</li>';
            }
        }
        
        return $status;        

    }

    public function dietNotStarted()
    {
        $patients = Patient::getDietNotStarted();

        //dd($patients);

        $data = array(
            'menu'      => $this->menu,
            'section'   => 'reports.diet_not_started',
            'patients'  => $patients
        );
        return view('home')->with($data);

    }

    public function appointments()
    {
        $appointments = Patient::getAppointments($this->date);
        $data = array(
            'menu'           => 'service',
            'section'        => 'appointments',
            'appointments'   => $appointments,
            'date'           => $this->date,
        );
        
        return view('home')->with($data);
    }

    public function showMessages()
    {
        $data = array(
            'menu'           => 'service',
            'section'        => 'reports.messages'
        );
        return view('home')->with($data);   
    }

    public function weightLoss()
    {
        $weightLoss = Patient::whereHas('fees',function($query) {
            $query->where('end_date','>=',DB::raw('curdate()'));
        })
        ->with('fees','lead','lead.programs')
        ->whereHas('lead.programs', function($q) {
                        $q->where('programs.id', 1);
                    })
        ->limit(env('DB_LIMIT'))
        ->get();

        


        $weightLoss = PatientWeight::weightLoss($weightLoss);

        $weightGain = Patient::whereHas('fees',function($query) {
            $query->where('end_date','>=',DB::raw('curdate()'));
        })
        ->with('fees','lead','lead.programs')
        ->whereHas('lead.programs', function($q) {
                        $q->where('programs.id', 2);
                    })
        ->limit(env('DB_LIMIT'))
        ->get();

        


        $weightGain = PatientWeight::weightLoss($weightGain);

        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'reports.weight_loss',
            'weightLoss'    =>  $weightLoss,
            'weightGain'    =>  $weightGain,
            'x'             =>  1,
            'y'             =>  1,
        );

        return view('home')->with($data);
    }

    public function verifySentDiet() 
    {    
           $diets = MasterDietCopy::where('isapproved' , 0)
                                ->orderBy('id' , 'desc')
                                ->paginate(50);           
           $data = array('menu'    =>  $this->menu,   
                         'section' =>  'approve_dietsent',
                         'diets'   =>  $diets,
                         'i'       =>  1,
                        );   
            return view('home')->with($data);
    }

    public function approveDiet($id)
    {        
        MasterDietCopy::where('id' , $id)                    
                    ->update(['isapproved' => 1]);         
        return $this->verifySentDiet();   
    }
    public function getMasterDiet()
    {
        $blood_groups = BloodGroup::get();
        $rh_factors	=	RhFactor::get();
        $programs = Program::get();
        $prakriti = Prakriti::get();
        $diet =     DB::table('master_diet AS md')
                        ->join('programs','md.Program_ID','=','programs.id')
                        ->join('MasterDietCondition','md.Condition_ID','=','MasterDietCondition.CID')
                        ->where('isapproved',0)
                        ->select('md.id','name','Blood_Group','Rh_Factor','Body_Prakriti','Day_Count','Breakfast','MidMorning','Lunch','Evening','Dinner','isveg')
                        ->orderBy('md.created_at','desc')
                        ->limit(15)
                        ->get();
          
                   
        $data = array(
            'menu'           =>  'service',
            'section'        =>  'addMasterDiet',
            'blood_groups'	=>	$blood_groups,
            'rh_factors'	=>	$rh_factors,
            'programs'      =>  $programs,
            'prakriti'      =>  $prakriti,
            'diets'    =>  $diet
            
        );
        return view('home')->with($data);
    }

    public function saveMasterDiet(Request $request)
    {   
        try{
            
            $prakriti = trim($request->prakriti_name);
            $rhfactor = trim($request->rhfactor_name);
            $bloodgroup = trim($request->blood_group_name);
            $program = trim($request->program_id);
            $Condition = DB::table('MasterDietCondition')
                        ->where('Blood_Group',$bloodgroup)
                        ->where('Rh_Factor',$rhfactor)
                        ->where('Body_Prakriti',$prakriti)
                        ->first();          
            $DayCount = DB::table('master_diet')
                        ->where('Condition_ID',$Condition->CID)
                        ->where('Program_ID',$program)
                        ->max('Day_Count');
                      
            $diet = new Master_Diet;
            $diet->Breakfast = trim($request->breakfast);
            $diet->MidMorning = trim($request->midmorning);
            $diet->Lunch = trim($request->lunch);
            $diet->Evening = trim($request->evening);
            $diet->Dinner = trim($request->dinner);
            $diet->Condition_ID = $Condition->CID;
            $diet->Program_ID = $program;
            $diet->Day_Count = $DayCount+1; 
            $diet->isveg = 1; 
            $diet->Added_by = Auth::user()->employee->name;   
            $diet->save();
        } catch (Illuminate\Database\QueryException $e) {
        echo $e;
        }

        return redirect('service/addMasterDiet');

    }

    public function showMasterDiet(Request $request)
    {
        $blood_group = BloodGroup::get();
        $rh_factor	=	RhFactor::get();
        $program = Program::get();
        $prakriti = Prakriti::get();
        $diet = null;
        $heading = null; 
        if($request->prakriti_name && $request->rhfactor_name && $request->blood_group_name && $request->program_id)
        {
            $prakritis = trim($request->prakriti_name);
            $rhfactors = trim($request->rhfactor_name);
            $bloodgroups = trim($request->blood_group_name);
            $programs = trim($request->program_id);
            $Condition = DB::table('MasterDietCondition')
                            ->where('Blood_Group',$bloodgroups)
                            ->where('Rh_Factor',$rhfactors)
                            ->where('Body_Prakriti',$prakritis)
                            ->first();
                                            
            $diet = DB::table('master_diet')
                        ->where('Condition_ID',$Condition->CID)
                        ->where('Program_ID',$programs)
                        ->where('isapproved',1)
                        ->orderby('Day_Count')
                        ->get();

            $program_name =  Program::where('id',$programs)->first();           
            $heading .= $program_name->name." (".$bloodgroups.$rhfactors." ".$prakritis.")";      
        }
        
        $data = array(
        'menu'           =>  'service',
        'section'        =>  'viewMasterDiet',
        'blood_groups'	=>	$blood_group,
        'rh_factors'	=>	$rh_factor,
        'programs'      =>  $program,
        'prakriti'      =>  $prakriti,
        'diets'         =>  $diet,
        'headings'      =>  $heading
        );
    return view('home')->with($data);                  
    }

    public function editMasterDiet($id)
    {       
            
            $diet = DB::table('master_diet')
                        ->where('id',$id)
                        ->first();
            $data = array(
                        'diet' => $diet,
                        'id'   => $id
            );
            return view('modals.masterdiet')->with($data);                                 
    }
    public function tagMasterDiet(Request $request)
    {       
            
            $diet = null;
            $olddiet =  Master_Diet::find($request->id);
            $condition = $olddiet->Condition_ID;
            $daycount = $olddiet->Day_Count;
            $program = $olddiet->Program_ID;
            if($request->breakfast && $request->mid_morning && $request->lunch && $request->evening && $request->dinner)
            {
                
                $diet = new Master_Diet;
                $diet->Breakfast = trim($request->breakfast);
                $diet->MidMorning = trim($request->mid_morning);
                $diet->Lunch = trim($request->lunch);
                $diet->Evening = trim($request->evening);
                $diet->Dinner = trim($request->dinner);
                $diet->Condition_ID = $condition;
                $diet->Program_ID = $program;
                $diet->Day_Count = $daycount;  
                $diet->isveg = $request->isveg;
                $diet->Added_by = Auth::user()->employee->name;
                $diet->save(); 
                return "Master Diet Added";
            }
            $data = array(
                        'diet' => $diet,
                        'condition' => $condition,
                        'id'   => $request->id,
                        'day' => $daycount,
                        'program' => $program

            );
            return view('modals.tagmasterdiet')->with($data);                               
    }

    public function updateMasterDiet(Request $request)
    {
            //dd($request->id);
            $diet = Master_Diet::find($request->id);
            $diet->Breakfast  = $request->breakfast;
            $diet->MidMorning = $request->mid_morning;
            $diet->Lunch      = $request->lunch;
            $diet->Evening    = $request->evening;
            $diet->Dinner     = $request->dinner;
            $diet->isveg      = $request->isveg;
            if(Auth::user()->hasRole('service')||(Auth::user()->hasRole('service_tl') && $diet->isapproved==1))
                $diet->isapproved = 1;
            else
                $diet->isapproved = 0;    
            $diet->save();

            return "Master Diet Updated";

    }

    public function verifyMasterDiet()
    {
        $diets = DB::table('master_diet AS md')
                    ->join('programs','md.Program_ID','=','programs.id')
                    ->join('MasterDietCondition','md.Condition_ID','=','MasterDietCondition.CID')
                    ->where('isapproved',0)
                    ->select('md.id','name','Blood_Group','Rh_Factor','Body_Prakriti','Day_Count','Breakfast','MidMorning','Lunch','Evening','Dinner','isveg','isapproved')
                    ->get();          

        $data = array(
                    'menu'           =>  'service',
                    'section'        =>  'verifyMasterDiet',
                    'diets'          =>   $diets
                     );            

        return view('home')->with($data);        

    }

    public function leave()
    {
        $users = User::getUsersByRole('nutritionist');
        $i = 1;

        $data = array(
                    'menu'           =>  'service',
                    'section'        =>  'leave',
                    'users'          =>   $users,
                    'i'              =>   $i
                     );            

        return view('home')->with($data);   
    }

    public function leaveapprove($id)
    {


        $nutritionist_name = User::where('id' , $id)
                             ->with('employee')
                             ->first();
        $leave = new NutritionistLeave;

        $leave->nutritionist_id = $nutritionist_name->employee->id;
        $leave->created_by = Auth::user()->employee->name;

        $leave->save();

        return $this->leave();

    } }
