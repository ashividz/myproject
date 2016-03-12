<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\PatientHerbMealtime;
use Auth;

class MealtimeController extends Controller
{

    public function savePatientMealtime(Request $request) {

    	if(!PatientHerbMealtime::isExistingMealtime($request->patient_herb_id, $request->mealtime_id))
        {
        	PatientHerbMealtime::updateMealtime($request);
        	return "Successfully saved";
        }

        return "Mealtime already exists";

        
    }
}