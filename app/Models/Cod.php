<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Carrier;

class Cod extends Model
{
	protected $table = 'cods';
	public $timestamps = false;

	public function carrier()
    {
    	return $this->belongsTo(Carrier::class);
    }

	public static function checkAvailability($pin)
	{
		if (trim($pin) == "") {
			return "Enter PIN";
		} else {

			$carriers = Carrier::with([
				'cods'=>function($query) use ($pin){
					$query->where('pin',$pin);
				}
			])
			->whereHas('cods',function($query) use ($pin){
				$query->where('pin',$pin);
			})
			->get();
			
			
			if ( $carriers->isEmpty() ) {
				return "Invalid PIN";
			}

			$message = '';
			foreach ($carriers as $carrier) {
				$cod = $carrier->cods->first();
				$message .= '<u>'.$carrier->name.'</u><br/>';
				$message .= "<b>Delivery : </b>";
				$message .= $cod->delivery == 1 ? "Available" : "<span class='red'>Not available</span>";
				$message .='<br/>';
				$message .= "<b>Cash On Delivery : </b>";
				$message .= $cod->cod == 1 ? "Available" : "<span class='red'>Not available</span>";	
				$message .='<br/>';

			}		
			
			return $message;
		}

	}

	/* changes on Aug 6,2016
	drop index cods_pin_unique on cods;
	alter table cods add  carrier_id int(3) unsigned not null;
	update cods set carrier_id=1;
	*/


}
