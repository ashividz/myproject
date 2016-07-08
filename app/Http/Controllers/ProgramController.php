<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Program;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::get();

        $data = array(
            'menu'          => 'settings',
            'section'       => 'program.index',
            'programs'      =>  $programs
        );

        return view('home')->with($data);
    }

    public function update(Request $request)
    {
       if (trim($request->value) == '') {
            return "Error: Cannot be Null";
        }

        $program = Program::where('name', $request->value)
                        ->where('id', '<>', $request->id)
                        ->first();
        if($program) {
            return "Error: Duplicate Name";
        }

        //return $request;

        $method = Program::find($request->id);
        $method->name =  $request->value;
        $method->save();

        return $request->value;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'                          => 'required|unique:programs'
        ]);

        $program = new Program;

        $program->create($request->all());
        
        $data = array(
            'message' => 'New Program <b><i>'.$request->name.'</i></b> added', 
            'status' => 'success'
        );    

        return back()->with($data);
    }

    public function get()
    {
        return Program::get();
    }
}