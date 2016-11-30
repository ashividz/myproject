<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\CreateOrderRequest;

use App\Models\Cart;
use App\Models\LeadProgram;
use App\Models\Program;
use App\Models\ProgramOrder;
use App\Models\Lead;
use Auth;
use Redirect;
use Carbon;
class LeadProgramController extends Controller
{
    public function show($id)
    {
        $lead = Lead::find($id);
        
        $data = array(
            'menu'      =>  'lead',
            'section'   =>  'partials.program',
            'lead'      =>  $lead,
        );

        return view('home')->with($data);
    }

    public function store(Request $request, $id)
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return "Lead Not found";
        }

        $lead->programs()->detach();
        foreach ($request->programs as $program) {
            echo $program;
            $lead->programs()->attach($program);
        }
        //$lead->programs()->create($request->programs);

        //Save the Programs
        //LeadProgram::store($id, $request->programs);

        //return Redirect::back()->with($data);
    }   

    public function get(Request $request)
    {
        $lead = Lead::find($request->id);
        return $lead->programs->pluck('pivot.program_id');
    }
}
