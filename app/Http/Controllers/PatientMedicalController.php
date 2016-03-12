<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Database\QueryException;

use App\Models\Patient;
use App\Models\BloodGroup;
use App\Models\RhFactor;

class PatientMedicalController extends Controller
{
	public function show($id)
	{
		$patient = Patient::find($id);

		$blood_groups = BloodGroup::get();

		$rh_factors	=	RhFactor::get();

		$data = array(
            'menu'      	=> 'patient',
            'section'  	 	=> 'partials.medical',
            'patient'  		=> 	$patient,
            'blood_groups'	=>	$blood_groups,
            'rh_factors'	=>	$rh_factors

        );

        return view('home')->with($data);
	}

	public function store(Request $request,$id)
	{
		$patient = Patient::find($id)->update($request->all());

		return $this->show($id);
	}
}