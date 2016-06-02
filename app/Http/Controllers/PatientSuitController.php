<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Database\QueryException;

use App\Models\Suit;
use Auth;

class PatientSuitController extends Controller
{
	public function store(Request $request, $id)
	{
        $suit = Suit::where('patient_id', $id)->first();

		if (!$suit) {
			$suit =  new Suit;
		}

		$suit->patient_id = $id;
		$suit->date = date('Y-m-d');
		$suit->suit = $request->suit ? : $suit->suit;
		$suit->not_suit = $request->not_suit ? : $suit->not_suit;
		$suit->trial_plan = $request->trial_plan ? : $suit->trial_plan;
		$suit->remark = $request->remark ? : $suit->remark;
		$suit->deviation = $request->deviation ? : $suit->deviation;
		$suit->created_by = Auth::id();
		$suit->save();

		return "Saved";
	}
}