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

class LeadProgramController extends Controller
{
    public function show($id)
    {
        $lead = Lead::with('programs')->find($id);
        $array = array();

        $programs = Program::get();

        foreach ($lead->programs as $program) {
            array_push($array, $program->pivot->program_id);
        }
        //dd($array);
        
        $data = array(
            'menu'      =>  'lead',
            'section'   =>  'partials.program',
            'lead'      =>  $lead,
            'programs'  =>  $programs,
            'array'     =>  $array
        );

        return view('home')->with($data);
    }

    public function store(Request $request, $id)
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return "Lead Not found";
        }

        //Save the Programs
        LeadProgram::store($id, $request->programs);

        $data = array(
            'message'           =>  'Program updated',
            'status'            =>  'success'
        );

        return Redirect::back()->with($data);
    }   
}
