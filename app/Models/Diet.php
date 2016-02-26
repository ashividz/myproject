<?php

namespace App\Models;

use Carbon\Carbon;
use Storage;
use Mail;
use Auth;
use DB;

use App\Support\SMS;

use Illuminate\Database\Eloquent\Model;

class Diet extends Model
{
    //protected $table = "diet_assign";
    
    //public $timestamps = false;

    public function getDates()
    {
       return ['date'];
    }

    public function patient()
    {
    	return $this->belongsTo(Patient::class, 'patient_id');
    }

    public static function getDiets($start_date, $end_date, $nutritionist = NULL)
    {
    	$query = Diet::with('patient.lead')
    			->whereBetween("date", array($start_date, $end_date));

    	if ($nutritionist) 
    	{
    		$query = $query->where("nutritionist", $nutritionist);
    	}
            
        return $query->limit(env('DB_LIMIT'))
                    ->get();
    }

    public static function deleteDiet($id)
    {
        return Diet::destroy($id);
    } 

    public static function send($request)
    {
        $patient = Patient::find($request->patient_id);

        $status = '';

        $diets = Diet::whereIn('id',$request->checkbox)->get();

        if($request->sms && $patient->lead->country == 'IN')
        {
            foreach ($diets as $diet) {

                $sms_response = Diet::sendSMS($diet);

                if ($sms_response) {
                    $status .= '<li>Diet SMS Sent</li>';
                    Diet::setSMSResponse($diet, $sms_response);
                }
            }                
        }

        if (trim($patient->lead->email) <> '') {
            $body = Diet::emailHeader($patient);
        
            foreach ($diets as $diet) {
                $body .= Diet::emailBody($diet);
            }                

            $body .= Diet::emailFooter();
            
            if(Diet::email($patient, $body)) {
                $status .= '<li>Diet Email Sent</li>';
                Diet::setEmailStatus($diets);
            }
        }
        else {
            $status .= '<li>Email does not exist for '.$patient->lead->name.'</li>';
        }

        return $status;
    }

    public static function sendSms($diet)
    {
        $patient = Patient::find($diet->patient_id);

        if ($patient->lead->mobile <> '') {

            $msg = "Dear ". $patient->lead->name."\n";
            $msg .=  "Date: ".$diet->date_assign."\n";
            //$msg .=  "Early Morning: ".$diet->early_morning."\n";
            $msg .=  "Breakfast: ".$diet->breakfast."\n";
            $msg .=  "Mid Morning: ".$diet->mid_morning."\n";
            $msg .=  "Lunch: ".$diet->lunch."\n";
            $msg .=  "Evening: ".$diet->evening."\n";
            $msg .=  "Dinner: ".$diet->dinner."\n";
            
            if(trim($diet->herbs) <> '') {
                $msg .=  "Herbs: ".$diet->herbs."\n";
            }

            if(trim($diet->rem_dev) <> '') {
                $msg .=  "Remarks: ".$diet->rem_dev."\n";
            }

            $msg .=  "Regards\n";
            $msg .=  "Nutritionist ".$diet->nutritionist."\n";
            $msg .=  "Nutri-Health\n";
            $msg .=  "011-49945900\n";

            $sms = new SMS;
            return $sms->send($patient->lead->mobile, $msg);
        }

        return false;
    }

    public static function emailHeader($patient)
    {
        $body = Storage::get('templates/diets/header.php'); 
        $body = str_replace('$patient', $patient->lead->name, $body); 

        return $body;
    }

    public static function emailBody($diet)
    {
        $patient = Patient::find($diet->patient_id);

        $body = Storage::get('templates/diets/content.php'); 
        $body = str_replace('$date', date('D, jS M, Y', strtotime($diet->date_assign)), $body); 
        $body = str_replace('$nutritionist', $patient->nutritionist, $body); 
        //$body = str_replace('$early_morning', $diet->early_morning, $body);
        $body = str_replace('$breakfast', $diet->breakfast, $body);
        $body = str_replace('$mid_morning', $diet->mid_morning, $body);
        $body = str_replace('$lunch', $diet->lunch, $body);
        $body = str_replace('$evening', $diet->evening, $body);
        $body = str_replace('$dinner', $diet->dinner, $body);
        $body = str_replace('$herbs', $diet->herbs, $body);
        $body = str_replace('$remarks', $diet->rem_dev, $body);

        return $body;
    }

    public static function emailFooter()
    {
        return Storage::get('templates/diets/footer.php');  
    }

    public static function email($patient, $body)
    {
        Mail::queue('templates.emails.empty', array('body' => $body), function($message) use ($patient)
        {
            $message->to($patient->lead->email, $patient->lead->name)
                ->bcc("diet@nutrihealthsystems.co.in")
                ->subject("Diet Plan - ".$patient->lead->name." - ".date('D, jS M, Y H:i:s'))
                ->from('diet@nutrihealthsystems.co.in', 'Nutri-Health Systems');
                //->setBody($body);
            
            //Add CC
            if (trim($patient->lead->email_alt) <> '') {
                $message->cc($patient->lead->email_alt, $patient->lead->name);
            }
        });

        return true;
    }

    private static function setSMSResponse($diet, $sms_response)
    {
        $diet->sms_response = $sms_response;
        $diet->save();
    }

    private static function setEmailStatus($diets)
    {   
        foreach ($diets as $diet) {
            $diet->email = true;
            $diet->save();
        }            
    }
}
