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
		foreach ($patients as $patient) {
			$upgradeDuration = Fee::getUpgradeDuration();
		    $endFee  		 = $patient->fees->sortByDesc('end_date')->first();
		    $startFee   	 = $endFee;	
		    $fees 	   		 = $patient->fees->sortByDesc('end_date');

		    foreach ($fees as $f ) {            
		        $diffInDays = $f->end_date->diffInDays($startFee->start_date,false);
		        if ( ($diffInDays <= $upgradeDuration))
		            $startFee = $f;
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
		    $patient->duration     =  $endFee->end_date->diffInDays($startFee->start_date);
		    $patient->startDate    =  $startFee->start_date;
		    $patient->endDate      =  $endFee->end_date;		    
		    if ( $patient->lead->height >0 ) {
				$initialWeight = $patient->initialWeight;
				$finalWeight   = $patient->finalWeight;
				$height = $patient->lead->height;
				$inches = $height/2.54;
		    	$patient->lead->feet = intval($inches/12);
		    	$patient->lead->inches = $inches%12;
		    	$patient->initialBMI = $initialWeight ? number_format($initialWeight->weight*100*100/pow($patient->lead->height,2) ,2):null ;
		    	$patient->finalBMI = $finalWeight ? number_format($finalWeight->weight*100*100/pow($patient->lead->height,2) ,2):null ;

		    }
		}

		return $patients;
	}	
}

/*alter table patient_weights add column updated_by int(4) default null;*/