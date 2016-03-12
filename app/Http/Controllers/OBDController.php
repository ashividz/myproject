<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OBD;
use App\Models\Lead;
use Auth;
use DB;

class OBDController extends Controller
{

	public function checkExisting()
	{
		$nos = array();

		$obds = OBD::get();

		foreach ($obds as $obd) {
			for ($i=$obd->begin; $i <= $obd->end  ; $i++) { 
				$nos[$i] = $i;
			}
		}

		$leads = Lead::whereIn("phone", $nos)->get();

		foreach ($leads as $lead) {
			echo $lead->name;
			echo " <a href='/lead/" . $lead->id . "/delete' target='_blank'>" . $lead->id . "</a> ". $lead->phone . "<p>";
		}
	} 

}