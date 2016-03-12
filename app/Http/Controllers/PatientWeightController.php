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
		$patient = Patient::with('fee')->find($id);

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
            'patient'		=>	$patient
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
}