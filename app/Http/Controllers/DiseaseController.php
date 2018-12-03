<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Models\Disease;
use App\Models\Unit;
use Auth;
use DB;
use Session;

class DiseaseController extends Controller
{
    public function show()
    {
        $diseases = Disease::orderBy('name')->get();


        $data = array(
            'menu'          =>  'doctor',
            'section'       =>  'disease',
            'diseases'       =>  $diseases,
            'i'             =>  1
        );

        return view('home')->with($data);
    }

    public function store(Request $request)
    {
        $disease = new Disease;
        $disease->name =  $request->disease;
        $disease->save();

        return $this->show();
    }

    public function update(Request $request)
    {

        if (trim($request->value) == '') {
            return "Error: Cannot be Null";
        }

        $disease = Disease::where('name', $request->value)
                        ->where('id', '<>', $request->id)
                        ->first();

        if($disease) {
            return "Error: Duplicate Name";
        }
        

        $disease = Disease::find($request->id);
        $disease->name =  $request->value;
        $disease->save();

        return $request->value;

    }

}