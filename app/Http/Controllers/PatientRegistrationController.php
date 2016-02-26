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
}