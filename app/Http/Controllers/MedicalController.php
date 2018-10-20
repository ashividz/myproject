<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\HerbRequest;

use App\Models\Lead;
use App\Models\Patient;
use App\Models\PatientHerb;

use App\Models\HerbTemplate;
use Auth;
use DB;

class MedicalController extends Controller
{
	

	public function savePatientHerb(HerbRequest $request)
	{
		return PatientHerb::saveHerb($request);
	}
}
