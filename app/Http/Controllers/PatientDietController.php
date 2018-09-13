<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Database\QueryException;

use App\Models\Patient;
use App\Models\Lead;
use App\Models\Diet;
use App\Models\Product;
use App\Models\PatientPrakriti;

use Auth;
use DB;

class PatientDietController extends Controller
{
	public function show($id , Request $request)
    {
        $patient     = Patient::with('herbs', 'diets', 'suit', 'weights', 'fee','lead')->find($id);
        $programs    = Patient::where('id' ,$id)
                                ->with('lead.programs')->first();
       
        $patient->productCart = $patient->lead->carts()
               ->whereHas('products',function($query) {
                    $query->whereIn('products.id',Product::getHerbIds());
               })
               ->orderBy('created_at','desc')
               ->first();  

        $patient->references = Lead::with('references.patient')
                              ->find($patient->lead->id);

       // return $patient->references;


        $diets = Diet::where('patient_id', $id)
                    ->orderBy('date_assign', 'desc')
                    ->limit(12)
                    ->get();


        $diet_date = $patient->diet ? date('Y-m-d', strtotime('+1 day', strtotime($patient->diet->date_assign))) : date('Y-m-d'); 
        $diet_date = strtotime($diet_date) >= strtotime(date('Y-m-d')) ? $diet_date : date('Y-m-d');

        $fee = $patient->cfee ? $patient->cfee : $patient->fee;
        $days = floor((strtotime($diet_date) - strtotime($fee->start_date))/(60*60*24));

        $mdiets = null;
        $updateddiet = null;
        foreach ($programs->lead->programs as $program) 
        {  
            $program_id = $program->id;
            break;
        }        
            
        if($request->program)
        {           
             $program_id  = $request->program; 
        }

        $patientprakriti = PatientPrakriti::prakriti($id);

        $blood_group = Patient::where('id' , $id) 
                   ->with('blood_type' , 'rh_factor')
                   ->first();
        if($blood_group->blood_type && $patientprakriti->first_dominant_name)
        {
            $user = DB::table('MasterDietCondition')
                    ->where('Blood_Group', $blood_group->blood_type->name)
                    ->where('Rh_Factor' , $blood_group->rh_factor->code)
                    ->where('Body_Prakriti' , $patientprakriti->first_dominant_name)
                    ->first();
                    
            $no_of_diet = DB::table('master_diet')
                    ->where('program_id' , $program_id)
                    ->where('Condition_ID' , $user->CID)
                    ->distinct('Day_Count')
                    ->count('Day_Count');

            if($no_of_diet!=0)
            {
                $days = $days%$no_of_diet ;
                if($days === 0)
                {
                    $days = $no_of_diet ;
                }
            }  
            else
                $days = 0;

            $mdiets = DB::table('master_diet')
                     ->where('Condition_ID' , $user->CID)
                     ->where('Day_Count' , $days)
                     ->where('Program_ID' , $program_id)  
                     ->where('isapproved' , 1)
                     ->orderby('isveg','desc')
                     ->get();
        }

            foreach($mdiets as $mdiet)
            {

                if($mdiet->isveg==1 && ($patient->isveg==1 ||$patient->isveg==0|| $patient->isveg==-1 ))
                    $updateddiet = $mdiet;
                elseif($mdiet->isveg==-1 && ($patient->isveg==0|| $patient->isveg==-1) )
                    $updateddiet = $mdiet;
                elseif($mdiet->isveg==0 && $patient->isveg==0)
                    $updateddiet = $mdiet;
            }
        $data = array(
            'menu'          => 'patient',
            'section'       => 'partials.diet',
            'patient'       =>  $patient,
            'mdiet'         =>  $updateddiet,
            'programs'      =>  $programs,
            'diets'         =>  $diets,
            'program_id'    =>  $program_id
        );

        return view('home')->with($data);
    }


    public function all($id)
    {
        $patient = Patient::with('herbs', 'diets', 'suit', 'weights')->find($id);

        //dd($patient);

        $diets = Diet::where('patient_id', $id)
                    ->orderBy('date_assign', 'desc')
                    ->get();

        $data = array(
            'menu'          => 'patient',
            'section'       => 'partials.diets',
            'patient'       =>  $patient,
            'diets'         =>  $diets,
            'i'				=>	'1'
        );

        return view('home')->with($data);   
    }

    public function savePreferece(Request $request)
    {
        $patient = Patient::find($request->id);
        $patient->isveg = $request->isveg;
        $patient->save();
        return redirect('patient/'.$request->id.'/diet');
    }
}