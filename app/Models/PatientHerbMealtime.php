<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class PatientHerbMealtime extends Model
{
    protected $table = "patient_herb_mealtime";

    public function mealtime()
    {
        return $this->hasOne(Mealtime::class, 'id', 'mealtime_id');
    }

    public static function isExistingMealtime($patient_herb_id, $mealtime_id)
    {
        $mealtime = PatientHerbMealtime::where('patient_herb_id', $patient_herb_id)
            ->where('mealtime_id', $mealtime_id)
            ->first();
        
        if ($mealtime) {
           return true;
        }
        return false;
    }

    public static function saveMealtime($patient_herb_id, $mealtime_id)
    {
        $mealtime = new PatientHerbMealtime;
        
        $mealtime->patient_herb_id = $patient_herb_id;
        $mealtime->mealtime_id = $mealtime_id;
        $mealtime->created_by = Auth::user()->employee->name;
        $mealtime->save();
    }

    public static function updateMealtime($request)
    {
        $mealtime = new PatientHerbMealtime;
        
        $mealtime->patient_herb_id = $request->patient_herb_id;
        $mealtime->mealtime_id = $request->mealtime_id;
        $mealtime->created_by = Auth::user()->employee->name;
        $mealtime->save();
    }

}