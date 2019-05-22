<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;



class StudentController extends Controller
{
    public function addStudent()
    {
        return view('addstudent');
    }
    
    public function store(Request $request)
    {
       $validatedData = $request->validate([
        'student_name' => 'required',
        'father_name' => 'required',
       ]);
       $sid = '1';
       Student::create([
            'student_id'=>$sid,
            'student_name'=>$request->get('student_name'),
            'father_name'=>$request->get('father_name'),
            'dob'=>$request->get('dob'),
            'address'=>$request->get('address'),
            'mobile_no'=>$request->get('mobile_no'),
            'pin_code'=>$request->get('pin_code'),
            'department'=>$request->get('department'),
            'description'=>$request->get('description'),
            'gender'=>$request->get('gender'),
            'country'=>$request->get('country'),
            'state'=>$request->get('state'),
            'city'=>$request->get('city'),
            'web_url'=>$request->get('web_url')

        ]);
       //$student->save();
       return redirect('addstudent')->with('message','Student Added Successfully...');
   }
}
