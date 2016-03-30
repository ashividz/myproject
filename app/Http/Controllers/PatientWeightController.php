<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\PatientWeightRequest;

use App\Models\Patient;
use App\Models\Days365;
use App\Models\PatientWeight;
use App\Models\YuWoW\Healthtrack;

use Auth;

class PatientWeightController extends Controller
{
	public function index($id)
	{
		$patient = Patient::with('fee')->find($id);

		//$email = $patient->lead->email;
		if($patient) {

			$this->fetchWeight($patient);

		}
		
		return $this->show($id);

	}

	private function fetchWeight($patient)
	{
		$start_date = $patient->fee->start_date;
		$end_date = date('Y-m-d');


		$measurement = PatientWeight::where('patient_id', $patient->id)
					->orderBy('id', 'desc')
					->limit(1)
					->first();

		if($measurement) {
			$start_date = date('Y-m-d', strtotime('+1 day', strtotime($measurement->date)));
		}
		
		$noOfDays = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24) + 1;

        $days = Days365::limit($noOfDays)->get();
        
        foreach ($days as $day) {

        	$date = date('Y-m-d', strtotime("+".$day->day." days", strtotime($start_date)));
            //echo $date;
			$yuwow = Healthtrack::getWeight($patient->lead->email, $date);

			//$measurement = PatientWeight::where

			if($yuwow) {

				$measurement = PatientWeight::where('patient_id', $patient->id)
								->where('date', $date)
								->first();

				if(!$measurement) {
					$measurement = new PatientWeight;
				}
				
				$measurement->patient_id = $patient->id;
				$measurement->date = $date;
				$measurement->weight = $yuwow->dch_weight;
				$measurement->save();
			}

				
		}
	}

	public function show($id)
	{
		$patient = Patient::with('fee','lead')->find($id);

		$start_date = $patient->fee->start_date;

		$end_date = date('Y-m-d');

		$measurement = PatientWeight::where('patient_id', $patient->id)
					//->orderBy('id', 'desc')
					->limit(1)
					->first();
		
		if($measurement) {
			$start_date = $measurement->date;
		}

		$noOfDays = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24) + 1;

        $noOfDays = $noOfDays > 365 ? 365 : $noOfDays;

        $days = Days365::limit($noOfDays)->get();

        $measurements = PatientWeight::where('patient_id', $patient->id)->get();

        $weight = null;

        $smooth_weight = null;
        
        $alpha = 0.3;

        foreach ($days as $day) {
        	
        	$measurement = null;

        	$date = date('Y-m-d', strtotime("+".$day->day." days", strtotime($start_date)));
        	
        	$day->date = $date;
        	
        	$measurement = $measurements->where('date', $date)->first();
        	
        	if ($measurement) {

        		
        		if($smooth_weight && $weight) {
        			$smooth_weight = round($alpha*$weight + (1-$alpha)*$smooth_weight, 2);
        		} else {
        			$smooth_weight = $measurement->weight;
        		}
	        		
				$weight = $measurement->weight;
        		
	        	
        		$day->weight = $measurement->weight;
        		$day->smooth_weight = $smooth_weight;
        	}
        	else {
        		$day->weight = null;
        		$smooth_weight = round($alpha*$weight + (1-$alpha)*$smooth_weight, 2);
        		$day->smooth_weight = $smooth_weight;
        	}
        	
        	//dd($day);
        }  

        //dd($days);  

        $data = array(
            'menu'      	=> 'patient',
            'section'  	 	=> 'partials.weight',
            'days'  		=> $days,
            'patient'		=> $patient,
            'measurements'	=> $measurements,
        );

        return view('home')->with($data);
	}

	public function store(PatientWeightRequest $request, $id)
	{
		$today = date('Y-m-d');
		$weight = PatientWeight::where('patient_id', $id)
					->where('date', $today)
					->first();
		if(!$weight) {
			$weight = new PatientWeight;
		}

		$weight->patient_id = $id;
		$weight->date = $today;
		$weight->weight = $request->weight;
		$weight->created_by = Auth::id();
		$weight->save();

		return $this->show($id);
	}

	public function copyWeightFromIfitter($id)
	{
		$patient = Patient::with('lead.yuwow','fee')->find($id);		
		$measurement = PatientWeight::where('patient_id', $patient->id)
					->orderBy('id', 'desc')
					->first();
		$start_date = $patient->fee->start_date;		
		$end_date = date('Y-m-d');
		$today 	  = date('Y-m-d');
		
		if($measurement && $measurement->date >= $patient->fee->start_date) {
			$start_date = date('Y-m-d', strtotime('+1 day', strtotime($measurement->date)));
		}		
		if($start_date < $today){
			$iFitterWeights = $this->fetchWeightFromIfitter($patient->lead->email, $start_date,$end_date);
			$updatedWeights = 0;
			$dates= array();
			if($iFitterWeights){
				foreach($iFitterWeights as $iFitterWeight){
					$weight = PatientWeight::where('patient_id', $patient->id)
						->where('date', $iFitterWeight->date)
						->first();
					if(!$weight) {
						$weight = new PatientWeight;
					}

					$weight->patient_id = $patient->id;
					$weight->date 	= $iFitterWeight->date;
					$weight->weight = $iFitterWeight->weight;
					$weight->created_by = Auth::id();
					$weight->save();
					if(!in_array($weight->date, $dates))
						$updatedWeights++;
					else
						$dates[] = $weight->date;
					
				}
				return 'Total weights updatedWeights :'.$updatedWeights;
			}    
			else
				return 'Sorry! Weight could not be updated from iFitter';
		}
		else
			return 'weight is up to date';
	}

	public function fetchWeightFromIfitter($email,$start_date,$end_date)
	{
		//$emailId = $patient->lead->id;
		$baseURI = "http://ifittrtest.azurewebsites.net/RestMerchantService.svc/GetUserReadings";
        $emailId = $email;
        $beginTimestamp = 1455793922;
        $merchantId     = "RQAOCUIHD40JSXSIOJ";
        $data = array("emailId" => $emailId, "$beginTimestamp" => $beginTimestamp, "merchantId" => $merchantId);
        $data_string = json_encode($data);                                                                                 
                                                                                                                     
       	$ch = curl_init($baseURI);                                                                     
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($data_string))                                                                       
        );                                                                                                                   
                                                                                                                     
        $result   = curl_exec($ch);
        $response = json_decode($result);
        $curlStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($curlStatus == 200) {
        	if($response->Status == 'OK'){
            	$records = $response->Data;
            	//dd(count($records));
            	$weights = array();
            	foreach($records as $record){
            		$obj = (object) [];
            		$obj->date = date('Y-m-d',$record->RecordedForDate);
            		$obj->weight = $record->Weight;
            		if($obj->date >= $start_date && $obj->date <=$end_date)
            			$weights[] = $obj;            		
            	}
            	if(count($weights)>0)
            		return $weights;
            	else
            		return null;        
           	}
        	else {
            	return null;
        	}        
    	} 
    	else         
        	return null;
   	}	

}