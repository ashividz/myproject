<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Patient;
use App\Models\PatientHerbMealtime;
use DB;
use Auth;

class PatientHerb extends Model
{
    protected $table = "patient_herb";

    public function herb()
    {
    	return $this->belongsTo(Herb::class, 'herb_id');
    }

    public function unit()
    {
    	return $this->belongsTo(Unit::class, 'unit_id');
    }

    /*public function mealtime()
    {
    	return $this->hasOne(Mealtime::class, 'id', 'mealtime_id');
    }*/

    public function mealtimes()
    {
        return $this->hasMany(PatientHerbMealtime::class, 'patient_herb_id');
    }

    public static function saveHerb($request)
    { 
        $herb = PatientHerb::where('patient_id', $request->id)
                    ->where('herb_id', $request->herb)
                    ->first();

        if($herb) {
            return "Herb already added";
        }

        $herb = new PatientHerb;
        $patient = Patient::find($request->id);



        if ($patient) {
            $herb->patient_id = $patient->id;
            $herb->herb_id = $request->herb;
            $herb->quantity = $request->quantity;
            $herb->unit_id = $request->unit;
            $herb->remark = $request->remark;
            $herb->created_by = Auth::user()->employee->name; 
            $herb->save();

            foreach ($request->mealtimes as $mealtime) {
                //var_dump($mealtime);
                PatientHerbMealtime::saveMealtime($herb->id, $mealtime);
            }

            return "Sucessfully saved";
        }

        return "Error";
    }

    public static function active($id, $state)
    {
        $herb = PatientHerb::find($id);
        
        $herb->deleted_at = NULL;
        $herb->deleted_by = NULL;

        if($state == 'false')
        {   
            //dd($state);
            $herb->deleted_at = date('Y-m-d h:i:s');
            $herb->deleted_by = Auth::user()->employee->name;
        }

        $herb->save();

        return true;
    }
}