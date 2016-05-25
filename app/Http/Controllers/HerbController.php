<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\HerbRequest;
use App\Http\Requests\HerbTemplateRequest;

use App\Models\Lead;
use App\Models\Patient;

use App\Models\Herb;
use App\Models\HerbTemplate;
use App\Models\HerbTemplateMealtime;
use App\Models\Mealtime;

use App\Models\Unit;
use Auth;
use DB;

class HerbController extends Controller
{
	public function herbs($id)
	{
        $patient = Patient::with(['herbs' => function($q) {
                        $q->withTrashed();
                    }])
                    ->with('lead')
					->find($id);

        $lead    = $patient->lead;

		$templates = HerbTemplate::get();

		$data = array(
            'menu'          =>  'patient',
            'section'       =>  'partials.herbs',
            'lead'			=>	$lead,
            'patient'       =>  $patient,
            'templates'		=>	$templates,
            'i'				=>	'1'
        );

        return view('home')->with($data);
	}

	public function template($id)
	{
		$templates =  HerbTemplate::with('herb', 'unit', 'mealtimes')->find($id);

		$templates->units = Unit::get();
		$templates->mealtime = Mealtime::where('herb', 1)->get();

		return $templates;
	}

	public function templateForm()
	{
		$herbs = Herb::get();

		$units = Unit::where('herb', 1)->get();

		$mealtimes = Mealtime::where('herb', 1)->get();

		$templates = HerbTemplate::with('mealtimes.mealtime')->get();

		$data = array(
            'menu'          =>  'doctor',
            'section'       =>  'herbs.templates',
            'herbs'			=>	$herbs,
            'units'			=>	$units,
            'mealtimes'		=>	$mealtimes,
            'templates'		=>	$templates,
            'i'				=> 	1
        );

        return view('home')->with($data);
	}

	public function templateSave(HerbTemplateRequest $request)
	{
		/*$herb = HerbTemplate::find($request->herb);

        if($herb) {
            return "Herb already added";
        }*/

        $herb = new HerbTemplate;

        $herb->herb_id = $request->herb;
        $herb->quantity = $request->quantity;
        $herb->unit_id = $request->unit;
        $herb->remark = $request->remark;
        $herb->created_by = Auth::user()->employee->name; 
        $herb->save();

        foreach ($request->mealtimes as $mealtime) {
            //var_dump($mealtime);
            HerbTemplateMealtime::saveMealtime($herb->id, $mealtime);
        }

        return "Sucessfully saved";
    }

    public function show()
    {
        $herbs = Herb::orderBy('name')->get();

        $data = array(
            'menu'          =>  'doctor',
            'section'       =>  'herbs.herb',
            'herbs'         =>  $herbs,
            'i'             =>  1
        );

        return view('home')->with($data);
    }

    public function store(HerbRequest $request)
    {
        $herb = new Herb;

        $herb->created_by = Auth::id();
        $herb->name =  $request->herb;
        $herb->save();

        return $this->show();
    }

    public function update(Request $request)
    {
        if (trim($request->value) == '') {
            return "Error: Cannot be Null";
        }

        $herb = Herb::where('name', $request->value)
                        ->where('id', '<>', $request->id)
                        ->first();
        if($herb) {
            return "Error: Duplicate Name";
        }
        //return $request;

        $herb = Herb::find($request->id);
        $herb->name =  $request->value;
        $herb->save();

        return $request->value;

    }
}
