<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\CallDisposition;
use App\Models\DialerCallDisposition;
use App\Models\Patient;
use App\Models\Lead;
use App\Models\AmazonLead;
use Auth;
use DB;

class DoctorController extends Controller
{
    protected $menu;
    protected $doctor;
    protected $daterange;
    protected $start_date;
    protected $end_date;
    protected $appointmentInterval;
    protected $connectedCallDuration;
    protected $productappointmentInterval;

    public function __construct(Request $request)
    {
        $this->menu = "doctor";        
        $this->doctor = isset($request->user) ? $request->user : Auth::user()->employee->name;
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/m/d 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');
        $this->appointmentInterval   = 20;
        $this->productappointmentInterval   = 10;
        $this->connectedCallDuration = '00:01:00'; //HH:mm:ss
 
    }

    public function index()
    {
        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'index'
        );

        return view('home')->with($data);
    }
    
    public function calls()
    {
        $users = User::getUsersByRole('doctor');

        $calls = CallDisposition::getDispositionsByUser($this->doctor, $this->start_date, $this->end_date);
        
        //dd($calls);
        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'calls',
            'users'         =>  $users,
            'name'          =>  $this->doctor,
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'calls'         =>  $calls,
            'i'             =>  '0'
        );

        return view('home')->with($data);
    }

    public function dialercalls($id)
    {
        $users = User::getUsersByRole('doctor');

        $doctorIds = $users->pluck('id')->toArray();
        $doctorsUserNames   = User::whereIn('id',$doctorIds)->get()->pluck('username')->toArray();
        $doctorsNames       = User::whereIn('id',$doctorIds)->with('employee')->get()->pluck('employee.name')->toArray();

        $patient = Patient::find($id);
        $lead = Lead::with(['dialerphonedispositions' => function($query) use($doctorsUserNames){
                $query->with('user')
                    ->whereIn('username',$doctorsUserNames)
                    ->orderBy('eventdate','desc')
                    ->limit(20);
                }])            
                ->with(['dialermobiledispositions' => function($query) use($doctorsUserNames){
                    $query->with('user')
                    ->whereIn('username',$doctorsUserNames)
                    ->orderBy('eventdate','desc')
                    ->limit(20);;
                }])
                ->with(['dispositions'=> function($query) use($doctorsNames) {
                    $query->whereIn('name',$doctorsNames)
                    ->orderBy('created_at','desc')
                    ->limit(20);;
                }])
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
            'lead'               =>   $lead,
            'dialer_dispositions'=>  $dialer_dispositions,
            'prefix'             =>  'Dr.',
            'i'                  =>  '0'
        );

        return view('lead.modals.dispositions')->with($data);
    }

   public function patients()
    {
        $users = User::getUsersByRole('doctor');

        $doctorIds = $users->pluck('id')->toArray();
        $doctorsUserNames   = User::whereIn('id',$doctorIds)->get()->pluck('username')->toArray();
        $today = date('Y-m-d');
        $dayMinus15   = date('Y-m-d', strtotime('- '.$this->appointmentInterval.' days', strtotime(date('Y-m-d'))));      

        $patients = Patient::select("patient_details.*")
            ->leftJoin(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                $join->on('patient_details.id', '=', 'f.patient_id');
            })
            ->where('f.end_date', '>=', date('Y-m-d'));
        if($this->doctor != ''){
            $patients = $patients->where('doctor', $this->doctor);
        }
            
        $patients =  $patients->with(['lead.dialerphonedisposition' => function($query) use($doctorsUserNames){
                $query->with('user')
                ->whereIn('username',$doctorsUserNames)
                ->where('duration','>=',$this->connectedCallDuration)
                ->orderBy('eventdate','desc');
            }])            
            ->with(['lead.dialermobiledisposition' => function($query) use($doctorsUserNames){
                $query->with('user')
                ->whereIn('username',$doctorsUserNames)
                ->where('duration','>=',$this->connectedCallDuration)
                ->orderBy('eventdate','desc');
            }])
            /*->with(['lead.dialerphonedispositions' => function($query) use($doctorsUserNames,$dayMinus15){
                $query->with('user')
                ->whereIn('username',$doctorsUserNames)
                ->where('duration','>=',$this->connectedCallDuration)
                ->where('eventdate','>=',$dayMinus15)
                ->orderBy('eventdate');
            }])            
            ->with(['lead.dialermobiledispositions' => function($query) use($doctorsUserNames,$dayMinus15){
                $query->with('user')
                ->whereIn('username',$doctorsUserNames)
                ->where('duration','>=',$this->connectedCallDuration)
                ->where('eventdate','>=',$dayMinus15)
                ->orderBy('eventdate');
            }])                        */
            ->with(['lead.phonecallback' => function($query) use($doctorsUserNames){
                $query->with('user')
                ->whereIn('username',$doctorsUserNames)
                ->where('callbackdate','>=',date('Y-m-d H:i:s'))
                ->orderBy('callbackdate');
            }])            
            ->with(['lead.mobilecallback' => function($query) use($doctorsUserNames){
                $query->with('user')
                ->whereIn('username',$doctorsUserNames)
                ->where('callbackdate','>=',date('Y-m-d H:i:s'))
                ->orderBy('callbackdate');
            }])
            ->with('bt','medical','fee','cfee')            
            ->get();


            $productspatients = Patient::where('doctor', $this->doctor)
                                ->has('currentProductFee')
                                ->with(['lead.dialerphonedisposition' => function($query) use($doctorsUserNames){
                                    $query->with('user')
                                    ->whereIn('username',$doctorsUserNames)
                                    ->where('duration','>=',$this->connectedCallDuration)
                                    ->orderBy('eventdate','desc');
                                }])
                                ->with(['lead.dialermobiledisposition' => function($query) use($doctorsUserNames){
                                    $query->with('user')
                                    ->whereIn('username',$doctorsUserNames)
                                    ->where('duration','>=',$this->connectedCallDuration)
                                    ->orderBy('eventdate','desc');
                                }])
                                ->with(['lead.phonecallback' => function($query) use($doctorsUserNames){
                                    $query->with('user')
                                    ->whereIn('username',$doctorsUserNames)
                                    ->where('callbackdate','>=',date('Y-m-d H:i:s'))
                                    ->orderBy('callbackdate');
                                }])            
                                ->with(['lead.mobilecallback' => function($query) use($doctorsUserNames){
                                    $query->with('user')
                                    ->whereIn('username',$doctorsUserNames)
                                    ->where('callbackdate','>=',date('Y-m-d H:i:s'))
                                    ->orderBy('callbackdate');
                                }])
                                ->with('lead' , 'productFee' , 'currentProductFee')
                                ->get();
         //dd($productspatients);
        
        $data = array(
            'menu'                      =>  $this->menu,
            'section'                   =>  'patients',
            'users'                     =>  $users,
            'patients'                  =>  $patients,
            'productspatients'          =>  $productspatients,
            'appointmentInterval'       =>  $this->appointmentInterval,
            'productappointmentInterval' => $this->productappointmentInterval,
            'connectedCallDuration'     =>  $this->connectedCallDuration,
            'name'                      =>  $this->doctor,
            'doctorsUserNames'          =>  $doctorsUserNames,
            'start_date'                =>  $this->start_date,
            'end_date'                  =>  $this->end_date,
            'i'                         =>  '1',
            'x'                         =>  '1',
            'p'                         =>  '1',
        );

        return view('home')->with($data);
    } 


   public function marketPlaceOtc(Request $request)
   {
       $patients = AmazonLead::whereBetween('created_at', [$this->start_date, $this->end_date])->get();

       $data = array(
            'menu'                      =>  $this->menu,
            'section'                   =>  'amazonotc',
            'patients'                  =>  $patients,
            'start_date'                =>  $this->start_date,
            'end_date'                  =>  $this->end_date,
            'i'                         =>  '1'
        );

       return view('home')->with($data);
   }       
}
