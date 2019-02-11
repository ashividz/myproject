<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

use App\Models\Fee;

use DB;

class PatientWeight extends Model
{
	protected $fillable = [
			'patient_id',
			'date',
			'weight',
			'created_by',
			'updated_by',
	];

	public static function weightLoss($patients)
	{

		$_2kg = 0;
		$_1kg = 0;
		$_0kg = 0;
		$lessthenzero = 0;
		foreach ($patients as $patient) {
			$upgradeDuration = Fee::getUpgradeDuration();
		    $endFee  		 = $patient->fees->sortByDesc('end_date')->first();
		    $startFee   	 = $endFee;	
		    $fees 	   		 = $patient->fees->sortByDesc('end_date');
		    $count = 0;

		    foreach ($fees as $f ) {            
		        $diffInDays = $f->end_date->diffInDays($startFee->start_date,false);
		        if ( ($diffInDays <= $upgradeDuration))
		        {
		            $startFee = $f;
		            $count++;
		        }
		        else
		            break;
		    }
		    $patient->initialWeight =  PatientWeight::where('patient_id',$patient->id)
		    						 ->where('weight','>',0)
		    						 ->where('date','>=',$startFee->start_date)
		    						 ->orderBy('date')
		    						 ->first();

		    $patient->finalWeight  =  PatientWeight::where('patient_id',$patient->id)
		    						 ->where('weight','>',0)
		    						 ->where('date','>=',$startFee->start_date)
		    						 ->orderBy('date','desc')
		    						 ->first();

		    $patient->current_program_initial_weight = PatientWeight::where('patient_id',$patient->id)
		    						 ->where('weight','>',0)
		    						 ->where('date','>=',$patient->fee->start_date)
		    						 ->orderBy('date')
		    						 ->first();

		    $patient->current_program_final_weight = PatientWeight::where('patient_id',$patient->id)
		    						 ->where('weight','>',0)
		    						 ->where('date','>=',$patient->fee->start_date)
		    						 ->orderBy('date','desc')
		    						 ->first();



		    $patient->totalUpgrade = $count;
		    $patient->duration     =  $endFee->end_date->diffInDays($startFee->start_date);
		    $patient->startDate    =  $startFee->start_date;
		    $patient->endDate      =  $endFee->end_date;	
		    $initialWeight = $patient->initialWeight;
			$finalWeight   = $patient->finalWeight;

			if($initialWeight && $finalWeight)
			{
			    $weightloss = ($patient->initialWeight->weight - $patient->finalWeight->weight)/($patient->duration/30);

			    if($weightloss >= 2)
			    {
			    	$_2kg++;
			    }
			    elseif ($weightloss >= 1 && $weightloss < 2) {
			     	$_1kg++;
			    }
			    elseif ($weightloss >= 0 && $weightloss < 1) {
			     	    	$_0kg++;
			     	    }
			    else{
			    	$lessthenzero++;
			    } 
			}	    
		    if ( $patient->lead->height >0 ) {
				
				$height = $patient->lead->height;
				$inches = $height/2.54;
		    	$patient->lead->feet = intval($inches/12);
		    	$patient->lead->inches = $inches%12;
		    	$patient->initialBMI = $initialWeight ? number_format($initialWeight->weight*100*100/pow($patient->lead->height,2) ,2):null ;
		    	$patient->finalBMI = $finalWeight ? number_format($finalWeight->weight*100*100/pow($patient->lead->height,2) ,2):null ;

		    }
		}
		$patients->g2kg = $_2kg;
		$patients->g1kg = $_1kg;
		$patients->g0kg = $_0kg;
		$patients->l0kg = $lessthenzero;

		return $patients;
	}	
}

/*alter table patient_weights add column updated_by int(4) default null;*/