<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Input;

use App\Models\Patient;
use App\Models\BloodGroup;
use App\Models\RhFactor;
use App\Models\PatientDisease;
use App\Models\Disease;

class PatientMedicalController extends Controller
{
	public function show($id)
	{
		$patient = Patient::find($id);

		$disease = Disease::all();
		$pd = PatientDisease::where('patient_id',$id)->get();

		$blood_groups = BloodGroup::get();

		$rh_factors	=	RhFactor::get();

		$data = array(
            'menu'      	=> 'patient',
            'section'  	 	=> 'partials.medical',
			'patient'  		=> 	$patient,
			'disease'		=>  $disease,
            'blood_groups'	=>	$blood_groups,
			'rh_factors'	=>	$rh_factors,
			'pd'			=>  $pd				
        );

        return view('home')->with($data);
	}

	public function store(Request $request,$id)
	{
		$patient = Patient::find($id)->update($request->all());

		return $this->show($id);
	}

	public function patientdetailsupdate(Request $request,$id)
	{
		// return $id;
		// die;
		//$patient = Patient::firstOrNew(array('id' => $id));
		$patient = Patient::find($id);		
		$patient->blood_group_id = $request->blood_group_id;
		$patient->rh_factor_id = $request->rh_factor_id;
		$patient->constipation = $request->constipation; 
		$patient->gas = $request->gas;
		$patient->water_retention = $request->water_retention;
		$patient->digestion_type = $request->digestion_type;
		$patient->allergic = $request->allergic;
		$patient->wheezing = $request->wheezing;
		$patient->acidity = $request->acidity;
		$patient->diseases_history = $request->diseases_history;
		$patient->energy_level = $request->energy_level;
		$patient->menstural_history = $request->menstural_history;
		$patient->bp_high = $request->bp_high;
		$patient->bp_low = $request->bp_low;
		$patient->diagnosis = $request->diagnosis;
		$patient->previous_weight_loss = $request->previous_weight_loss;
		$patient->sweet_tooth = $request->sweet_tooth;
		$patient->routine_diet = $request->routine_diet;
		$patient->special_food_remark = $request->special_food_remark;
		
		if(!empty($request->medical_history)){
			$patient->medical_history = implode(',', $request->medical_history);
		}else{
			$patient->medical_history = $request->medical_history;
		}
		if(!empty($request->medical_problem)){
			$patient->medical_problem = implode(',', $request->medical_problem);
		}else{
			$patient->medical_problem = $request->medical_problem;
		}
		
		$patient->save();
		if (!empty($request->medical_history)){
			$rcomh = explode(',', $request->assignMedicalHistory);
            foreach ($rcomh as $key => $n){
			    $patientDisease = new PatientDisease;
			    $patientDisease->patient_id = $request->txtPatientId;
			    $patientDisease->disease_id = $rcomh[$key];
			    $patientDisease->is_past = $request->is_past_active;
			    $patientDisease->save();
			}
   		}
   		if (!empty($request->medical_problem)){
   			$rcomp = explode(',', $request->assignMedicalProblem);
            foreach ($rcomp as $key => $n){
			    $patientDisease = new PatientDisease;
			    $patientDisease->patient_id = $request->txtPatientId;
			    $patientDisease->disease_id = $rcomp[$key];
			    $patientDisease->save();
			}
   		}
		return redirect()->back()->with('message', 'DATA SUBMITTED SUCCESSFULLY!');
	}
}