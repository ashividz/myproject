<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

use App\Models\Patient;

class Nutritionist extends Model
{
    protected $table = 'patient_nutritionist';

    public static function getPrimaryPatientCount($nutritionist)
    {
    	return Nutritionist::select(DB::raw('COUNT(*) AS count'))
    				->where('nutritionist', $nutritionist)
                    ->where('secondary', 0)
    				->first();
    }

    public static function getSecondaryPatientCount($nutritionist)
    {
    	return Nutritionist::select(DB::raw('COUNT(*) AS count'))
    				->where('nutritionist', $nutritionist)
    				->where('secondary', 1)
    				->first();
    }

    public static function ifMultipleNtrOnSameDate($request)
    {
        $secondary = isset($request->secondary) ? $request->secondary : 0;

        $nutritionist = Nutritionist::where('patient_id', $request->id)
                        ->where('created_at', '>=', DB::raw('CURDATE()'))
                        ->where('secondary', $secondary)
                        ->first();
        
        if (isset($nutritionist)) {
            return true;
        }

        return false;
    }

    public static function ifSameNutritionist($request)
    {
        $secondary = isset($request->secondary) ? $request->secondary : 0;

        $nutritionist = Nutritionist::where('patient_id', $request->id)
                        //->where('nutritionist', $request->value)
                        ->where('secondary', $secondary)
                        ->orderBy('id', 'desc')
                        ->first();

                        //dd($nutritionist->nutritionist);
        
        if ($nutritionist && $nutritionist->nutritionist == $request->value) {
            return true;
        }

        return false;
    }

    public static function ifSamePrimarySecondaryNutritionist($request)
    {
        $secondary = isset($request->secondary) ? $request->secondary : 0;

        $nutritionist = Nutritionist::where('patient_id', $request->id)
                        //->where('nutritionist', $request->value)
                        ->where('secondary', !$secondary)
                        ->orderBy('id', 'desc')
                        ->first();

        if ($nutritionist && $nutritionist->nutritionist == $request->value) {
            return true;
        }

        return false;
    }

    public static function assignNutritionist($request)
    {
        $secondary = isset($request->secondary) ? $request->secondary : 0;

        $patient = Patient::find($request->id);
        
        $nutritionist = new Nutritionist;

        $nutritionist->patient_id = $request->id;
        $nutritionist->nutritionist = $request->value;
        $nutritionist->secondary = $secondary;
        $nutritionist->created_by = Auth::user()->employee->name;
        $nutritionist->save();

        return $nutritionist;
    }

    //Update Nutritionist name in Patient Table. To be decrepated afterwards
    public static function updateNutritionist($request)
    {
        $patient = Patient::find($request->id);

        if ($request->secondary) 
        {
            $patient->secondary_nutritionist = $request->value;
        }
        else
        {
            $patient->nutritionist = $request->value;
        }

        $patient->save();
    }
}
