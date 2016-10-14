<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\PatientRegistrationRequest;

use App\Models\Patient;
use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\Fee;
use App\Models\User;
use App\Models\Source;
use App\Models\Log;
use App\Models\Diet;
use Auth;
use DB;

class PatientRegistrationController extends Controller
{
	protected $daterange;
    protected $start_date;
    protected $end_date;

    public function __construct()
    {
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/m/d 00:00:00");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');
    }

	public function index($id)
	{
		$lead = Lead::find($id);

		$users = User::getUsersByRole('cre');

		$sources = Source::get();

		$data = array(			
            'menu'              =>  'patient',
            'section'           =>  'register',
            'lead'             	=>  $lead,
            'users'				=>	$users,
            'sources'			=>	$sources
        );
        return view('home')->with($data);
	}

	public function store(PatientRegistrationRequest $request, $id)
	{
		/** Update Lead **/
		$lead = Lead::updateLead($id, $request);

		$patient = Patient::where('lead_id', $id)->first();

		if (!$patient) {
			$patient = new Patient;
		}

		$patient->lead_id = $id;
		$patient->clinic = $lead->clinic;
		$patient->enquiry_no = $lead->enquiry_no;
		$patient->registration_no = Patient::getRegistrationNo($lead->clinic);
		$patient->created_by = Auth::id();
		$patient->save();

		$status = "Patient Registered. Please enter fee details";
		//return $this->index($id)->with('status', $status);
		return redirect('patient/'.$patient->id.'/fee')->with('status', $status);
	}



    public function showPatientFeeStatus()
    {
        $patients = Patient::with('fee')
                    ->whereBetween('created_at', array($this->start_date, $this->end_date))
                    ->get();
        //dd($patients);
        
        $data = array(
            'menu'          => 'reports',
            'section'       => 'registration.fees',
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'patients'      => $patients,
            'i'             => 1
        );
        return view('home')->with($data);

    }

    public function showLateStart()
    {
        $data = array(
            'menu'          => 'reports',
            'section'       => 'registration.lateStart',
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,            
            'i'             => 1
        );
        return view('home')->with($data);        
    }

    public function getLateStart(Request $request)
    {
        $feeIds = Log::where('owner_type','App\Models\Fee')
                    ->whereBetween('created_at',array($request->start_date,$request->end_date))
                    ->select(DB::raw('distinct(owner_id)'))
                    ->get()->pluck('owner_id')->toArray();        
        $total =    Fee::whereIn('id',$feeIds)->select(DB::raw('distinct(patient_id)'))->count();

        $limit   =  $request->limit  && $request->limit >= 0  && $request->limit <= 1000  ? $request->limit  : '20';
        $offset  =  $request->offset && $request->offset >= 0 && $request->offset < $total ? $request->offset : '0';

        $patientIDs = Fee::whereIn('id',$feeIds)
                      ->select(DB::raw('distinct(patient_id)'))
                      ->orderBy('entry_date')
                      ->take($limit)
                      ->skip($offset)
                      ->get()
                      ->pluck('patient_id')->toArray(); 

        $patients =  Patient::whereIn('id',$patientIDs)
                        ->with([                                
                            'fees' => function($query) use($request) {
                                $query->whereHas('log',function($q) use($request){
                                        $q->whereBetween('created_at',array($request->start_date,$request->end_date))
                                            ->where('type','updated')
                                            ->where('route','Job:AutoAdjustStartDate');
                                    })
                                ->with(['log' => function($q) use($request) {
                                        $q->whereBetween('created_at',array($request->start_date,$request->end_date))
                                            ->where('type','updated')
                                            ->where('route','Job:AutoAdjustStartDate');
                                    }
                                ])
                                ->with([                                    
                                    'source',
                                    'currency'
                                ]);
                            }
                        ])
                        ->with([
                            'lead' => function($query){
                                $query->select(DB::raw('id,name'));
                            },                            
                        ])
                        ->get();

        foreach ($patients as $patient) {
            foreach ($patient->fees as $fee ) {
                $diet =  Diet::where('date_assign','>=',$fee->log->old_value->start_date)
                        ->where('patient_id',$fee->patient_id)
                        ->whereRaw('IFNULL(diets.email,0) = 1')
                        ->orderBy('date_assign')
                        ->first();                
                if($diet)
                    $fee->first_diet = $diet->date_assign;
            }
        }        
        return json_encode(['patients' => $patients, 'total' => $total]);
    }
}