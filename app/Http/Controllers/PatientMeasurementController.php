<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Patient;
use App\Models\Days365;
use App\Models\PatientMeasurement;
use App\Models\YuWoW\Healthtrack;

use App\Http\Requests\PatientMeasurementRequest;
use Auth;

use DB;


class PatientMeasurementController extends Controller
{
    public function index($id)
    {
        $patient = Patient::with('fee')->find($id);     
        return $this->show($id);

    }   

    public function show($id)
    {
        $patient = Patient::with('fee', 'measurements' , 'lead')->find($id);

        $vediqueDiet =  DB::connection('VediqueDiet')
                        ->table('user_measurement')
                        ->where('email' , $patient->lead->email)
                        ->orderBy('id' , 'DESC')
                        ->get();

        //dd($vediqueDiet);

        $start_date = $patient->fee->start_date;

        $end_date = date('Y-m-d');

        $data = array(
            'menu'          => 'patient',
            'section'       => 'partials.measurements',
            'patient'       =>  $patient,
            'vediqueDiet'   =>  $vediqueDiet
        );

        return view('home')->with($data);
    }

    public function store(PatientMeasurementRequest $request, $id)
    {
        $measurement = new PatientMeasurement;
        $measurement->patient_id    = $id;
        $measurement->chest         = $request->chest;
        $measurement->waist         = $request->waist;
        $measurement->thighs        = $request->thighs;
        $measurement->hips          = $request->hips;
        $measurement->arms          = $request->arms;
        $measurement->abdomen       = $request->abdomen;
        $measurement->bp_systolic   = $request->bp_systolic;
        $measurement->bp_diastolic  = $request->bp_diastolic;
        $measurement->created_by    = Auth::id();
        $measurement->save();        
        return $this->show($id);
    }


    public function copy($id)
    {
        $patientMeasurement =  PatientMeasurement::where('patient_id', $id)->first();

        if(!$patientMeasurement) {

            $patient = Patient::find($id);
            
            $measurements = DB::table('fitness_details')
                        ->where('clinic', $patient->clinic)
                        ->where('registration_no', $patient->registration_no)
                        ->get();
            
            if($measurements) {                
                
                foreach($measurements as $measurement)
                {
                    $bp = explode('/', $measurement->bp);
                    $bp_systolic  = intval( trim($bp[0]) );
                    $bp_diastolic = intval( trim($bp[1]) );

                    DB::table('patient_measurements')->insert(
                        [
                            'patient_id'    =>  $patient->id,
                            'arms'          =>  $measurement->arms,
                            'chest'         =>  $measurement->chest,
                            'abdomen'       =>  $measurement->abdomen,
                            'hips'          =>  $measurement->hips,
                            'waist'         =>  $measurement->waist,
                            'thighs'        =>  $measurement->thighs,
                            'bp_systolic'   =>  $bp_systolic,
                            'bp_diastolic'  =>  $bp_diastolic,
                            'created_by'    =>  Auth::id(),
                            'created_at'    =>  $measurement->date,
                            'updated_at'    =>  $measurement->date,
                        ]
                    );

                }                

                return "Measurements copied";

            } else {
                return "Measurements not available";
            }
        }

        return "Measurements already exist";
    }
}