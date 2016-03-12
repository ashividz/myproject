<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

use App\Models\Patient;

class Doctor extends Model
{
    protected $table = 'patient_doctor';
    
    public static function ifSameDoctor($request)
    {
        $doctor = Doctor::where('patient_id', $request->id)
                        ->where('name', $request->value)
                        ->first();
        
        if (isset($doctor)) {
            return true;
        }

        return false;
    }
    
    public static function assignDoctor($request)
    {
        $patient = Patient::find($request->id);
        
        $doctor = new Doctor;

        $doctor->patient_id = $request->id;
        $doctor->clinic = $patient->clinic;
        $doctor->registration_no = $patient->registration_no;
        $doctor->name = $request->value;
        $doctor->created_by = Auth::user()->employee->name;
        $doctor->save();

        return $doctor;
    }

    //Update doctor name in Patient Table. To be decrepated afterwards
    public static function updateDoctor($request)
    {
        $patient = Patient::find($request->id);

        $patient->doctor = $request->value;

        $patient->save();
    }
}
