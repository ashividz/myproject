<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\Helper;

use App\Support\ArraySorter;

class PatientPrakriti extends Model
{
	protected $fillable = ['patient_id', 'prakriti_question_id', 'prakriti_id'];

	public static function prakriti($id)
	{
		$patient = Patient::find($id);

		$prakritis = Prakriti::get();

		$answers = PatientPrakriti::where('patient_id', $id)->get();

		if($answers) {

			foreach ($prakritis as $prakriti) {
				$a[$prakriti->id]['id'] = $prakriti->id;
				$a[$prakriti->id]['name'] = $prakriti->name;
				$a[$prakriti->id]['count'] = $answers->where('prakriti_id', $prakriti->id)->count();
			}

			//Helper::array_sort_by_column($a, 'count');
			$sorter = new ArraySorter('count');    
			usort($a, array($sorter, "sort"));


			$total = array_sum(array_column($a, 'count')); 

			$patient->first_dominant_id = $a[2]['id'];
			$patient->first_dominant_name = $a[2]['name'];
			$patient->first_dominant_count = $a[2]['count'];

			$patient->first_dominant_percentage = $total > 0 ? $a[2]['count']/$total*100 : 0;

			$patient->second_dominant_id = $a[1]['id'];	
			$patient->second_dominant_name = $a[1]['name'];	
			$patient->second_dominant_count = $a[1]['count'];	
			$patient->second_dominant_percentage = $total > 0 ? $a[1]['count']/$total*100 : 0;

			$patient->recessive_id = $a[0]['id'];
			$patient->recessive_name = $a[0]['name'];
			$patient->recessive_count = $a[0]['count'];
			$patient->recessive_percentage = $total > 0 ? $a[0]['count']/$total*100 : 0;
		}

			

		return $patient;
	}

	
}
