<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fees extends Model
{
    
    protected $table = "fees_details";

    public static function programStatus($id)
    {
    	$lead = Lead::with('patient')
    				->find($id);

    	$fee = Fees::where('clinic', $lead->clinic)
    				->where('registration_no', $lead->registration_no)
    				->orderBy('end_date', 'DESC')
    				->first();

    	if($fee) {
    		

	    	$start_date = $fee->start_date;
			$end_date = $fee->end_date;
			$now = date('Y-m-d') > $end_date ? $end_date : date('Y-m-d');

			$days = floor((strtotime(date($now)) - strtotime($start_date))/(60*60*24));
			$daysLeft = 0;
			$totalDays = floor((strtotime($end_date) - strtotime($start_date))/(60*60*24));
			if ($totalDays <> 0) {
			 	$progressPercentage = floor((($days)/$totalDays)*100);
			 } 

			$message = "<div class='alert ";

			if ($start_date > date('Y-m-d')) {
				$message .= "alert-danger' role='alert'><span class='fa fa-warning'></span>";
				$message .= "<span style='vertical-align:super'>";
				$message .= " Program has not started.";
				$message .= "</span>";
			}
			elseif ($end_date < date('Y-m-d')) 
			{
				$message .= "alert-danger' role='alert'><span class='fa fa-warning'></span>";
				$message .= "<span style='vertical-align:super'>";
				$message .= " Program has expired.";
				$message .= "</span>";
			}
			elseif ($end_date == date('Y-m-d')) 
			{
				$message .= "alert-warning' role='alert'><span class='fa fa-info-circle'></span>";
				$message .= "<span style='vertical-align:super'>";
				$message .= " Program will expire today.";
				$message .= "</span>";
			}
			elseif ($end_date > date('Y-m-d'))
			{
				$daysLeft = floor((strtotime($end_date) - strtotime(date('Y-m-d')))/(60*60*24));
				$message .= "alert-success' role='alert'><span class='fa fa-info-circle'></span>";
				$message .= "<span style='vertical-align:super'>";
				$message .= " Program will expire in " . $daysLeft;		
				$message .= ($daysLeft == 1) ? " day." : " days.";
				$message .= "</span>";
			}
			else
			{
				$message .= "alert-danger' role='alert'><span class='fa fa-warning'></span>";
				$message .= "<span style='vertical-align:super'>";
				$message .= " There is something wrong with your End Date!";
				$message .= "</span>";
			}
			$message .= "</div>";

			return $message;
		}
    }
}
