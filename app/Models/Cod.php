<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cod extends Model
{
	protected $table = 'cods';
	public $timestamps = false;

	public static function checkAvailability($pin)
	{
		if (trim($pin) == "") {
			return "Enter PIN";
		}

		$cod = Cod::where('pin', $pin)->first();

		if (!isset($cod)) {
			return "Invalid PIN";
		}

		$message = "<b>Delivery : </b>";
		$message .= $cod->delivery == 1 ? "Available" : "<span class='red'>Not available</span>";
		$message .= "<p/><b>Cash On Delivery : </b>";
		$message .= $cod->cod == 1 ? "Available" : "<span class='red'>Not available</span>";

		return $message;

	}
}
