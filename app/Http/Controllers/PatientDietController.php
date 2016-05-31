<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Database\QueryException;

use App\Models\Patient;
use App\Models\Diet;

use Auth;
use DB;

class PatientDietController extends Controller
{
	public function show($id)
    {
        //DB::update("UPDATE diet_assign AS f SET patient_id = (SELECT id FROM patient_details p WHERE p.clinic=f.clinic AND p.registration_no=f.registration_no) WHERE patient_id = 0");

        $patient = Patient::with('herbs', 'diets', 'suit', 'weights', 'fee')->find($id);
      
        $diets = Diet::where('patient_id', $id)
                    ->orderBy('date_assign', 'desc')
                    ->limit(12)
                    ->get();

        $data = array(
            'menu'          => 'patient',
            'section'       => 'partials.diet',
            'patient'       =>  $patient,
            'diets'         =>  $diets
        );

        return view('home')->with($data);   
    }

    public function all($id)
    {
        $patient = Patient::with('herbs', 'diets', 'suit', 'weights')->find($id);

        //dd($patient);

        $diets = Diet::where('patient_id', $id)
                    ->orderBy('date_assign', 'desc')
                    ->get();

        $data = array(
            'menu'          => 'patient',
            'section'       => 'partials.diets',
            'patient'       =>  $patient,
            'diets'         =>  $diets,
            'i'				=>	'1'
        );

        return view('home')->with($data);   
    }
}