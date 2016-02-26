<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\PrakritiQuestion;
use App\Models\PatientPrakriti;
use App\Models\Patient;
use DB;

class PrakritiController extends Controller
{
    protected $menu;
    protected $daterange;
    protected $start_date;
    protected $end_date;

    public function __construct()
    {
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/10/28 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');
 
    }

    public function constitution($id)
    {
        $data = array(
            'menu'          => 'patient',
            'section'       => 'constitution'
        );

        return view('home')->with($data);
    }

    public function prakriti(Request $request, $id)
    {
    	isset($request->_token) ? dd($request) : "";

        $questions = PrakritiQuestion::get();


        $data = array(
            'menu'      	=> 'patient',
            'section'  	 	=> 'prakriti',
            'questions'  	=> $questions,
            'i'         	=> '1'
        );

        return view('home')->with($data);
    }

    public function copy($id)
    {
        $pp =  PatientPrakriti::where('patient_id', $id)->first();

        if(!$pp) {

            $patient = Patient::find($id);


            $constution = DB::table('constitution')
                        ->where('clinic', $patient->clinic)
                        ->where('registration_no', $patient->registration_no)
                        ->first();
            if(isset($constution->detail)) {
            
                $details =  json_decode($constution->detail);

                /*$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '1'));
                $prakriti->question_id = 1;
                $prakriti->prakriti_id = $details->lifestyle;
                $prakriti->save();*/


                $prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '2'));
                $prakriti->question_id = 2;
                $prakriti->prakriti_id = $details->activity;
                $prakriti->save();

                $prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '3'));
                $prakriti->question_id = 3;
                $prakriti->prakriti_id = $details->bodyframe;
                $prakriti->save();

                $prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '4'));
                $prakriti->question_id = 4;
                $prakriti->prakriti_id = $details->circulation;
                $prakriti->save();

                $prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '5'));
                $prakriti->question_id = 5;
                $prakriti->prakriti_id = $details->appetite;
                $prakriti->save();

                /*$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '6'));
                $prakriti->question_id = 6;
                $prakriti->prakriti_id = $details->bowel;
                $prakriti->save();*/

                $prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '7'));
                $prakriti->question_id = 7;
                $prakriti->prakriti_id = $details->digestion;
                $prakriti->save();

                $prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '8'));
                $prakriti->question_id = 8;
                $prakriti->prakriti_id = $details->hair;
                $prakriti->save();

                $prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '9'));
                $prakriti->question_id = 9;
                $prakriti->prakriti_id = $details->skin;
                $prakriti->save();

                $prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '10'));
                $prakriti->question_id = 10;
                $prakriti->prakriti_id = $details->sleep;
                $prakriti->save();

                $prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '11'));
                $prakriti->question_id = 11;
                $prakriti->prakriti_id = $details->sweat;
                $prakriti->save();

                $prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '12'));
                $prakriti->question_id = 12;
                $prakriti->prakriti_id = $details->taste;
                $prakriti->save();

                $prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '13'));
                $prakriti->question_id = 13;
                $prakriti->prakriti_id = $details->thirst;
                $prakriti->save();

                $prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '14'));
                $prakriti->question_id = 14;
                $prakriti->prakriti_id = $details->voice;
                $prakriti->save();


                $prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '15'));
                $prakriti->question_id = 15;
                $prakriti->prakriti_id = $details->weather;
                $prakriti->save();

                $prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '16'));
                $prakriti->question_id = 16;
                $prakriti->prakriti_id = $details->weight;
                $prakriti->save();

                $prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '17'));
                $prakriti->question_id = 17;
                $prakriti->prakriti_id = $details->temperament;
                $prakriti->save();

                /*$prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '18'));
                $prakriti->question_id = 18;
                $prakriti->prakriti_id = $details->social;
                $prakriti->save();*/

                $prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '19'));
                $prakriti->question_id = 19;
                $prakriti->prakriti_id = $details->speech;
                $prakriti->save();

                $prakriti =  PatientPrakriti::firstorNew(array('patient_id' => $id, 'question_id' => '20'));
                $prakriti->question_id = 20;
                $prakriti->prakriti_id = $details->memory;
                $prakriti->save();

                return "Prakriti copied";

            } else {
                return "Prakriti not available";
            }
        }

        return "Prakriti already exists";
    }
}