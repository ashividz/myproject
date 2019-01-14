<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\VediqueDietPaidDiet;
use Storage;
use Mail;
use Auth;
use DB;

use App\Support\SMS;
use GuzzleHttp\Client;

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

    public static function herbs($id)
    {
        $patient = Patient::find($id);

        $doctor = $patient->doctor;

       // dd($doctor);

        $herbs = '';
        $message = '';
        if ($patient->herbs)
        {

            foreach ($patient->herbs as $herb) {

                $when = '';

                $herbs .= $herb->herb->name." : ".$herb->quantity." ";
                $herbs .= $herb->unit?$herb->unit->name:"";
                $herbs .= " ".$herb->remark;

                //if(isset($herb->mealtimes)) {
                    foreach ($herb->mealtimes as $mealtime) {
                        $when .= $mealtime->mealtime ? $mealtime->mealtime->name . ' & ' : '' ;
                    }
                //}     

                $when = rtrim($when, "& ");
                $herbs .= ' ('.$when.') '; 
                $herbs .= " \n ";
            }
        }

        $herbs = rtrim($herbs, " + ");

        if (trim($patient->lead->email) <> '') {
            $body = Diet::herbsHeader($patient);
            $body .= Diet::herbsBody($herbs , $doctor);
            $body .= Diet::emailFooter();
            
            if(Diet::herbsEmail($patient, $body)) {
                $message .= '<li>Herbs Email Sent</li>';
                $status = 'success';
                //Diet::setEmailStatus($diets);
            }
        }
        else {
            $message .= '<li>Email does not exist for '.$patient->lead->name.'</li>';
            $status = 'error';
        }

        $data = array(
            'message'       =>  $message,
            'status'        =>  $status
        );

        return $data;    
    }


    public static function send($request)
    {
        $patient = Patient::find($request->patient_id);

        $message = '';
        $status = '';
        $code = 'LbJdWrcNoF4pFTUJs0DaqKt33lpLDEReFT5oFzZQUy4pUNxl3B30dgzfFCgjIeKR';

        $diets = Diet::whereIn('id',$request->checkbox)->orderBy('date_assign')->get();
       
        if($patient->app)
        {         
            foreach ($diets as $diet) {
                $user = Patient::find($diet->patient_id) ;
                $email = $user->lead->email;
                break;
            }

            Diet::AddVediqueDiet($diets , $email);
            $client = new Client();
            $app_response = $client->request('POST', 'https://portal.yuwow.com/index.php/diet/insertDiet',[
                    'form_params' => [
                    'diet' => json_encode($diets),
                    'email' => json_encode($email)]
                    ]);
            
            

            $message .= '<li>Diet sent on YuWoW</li>';
            $status  = 'success'; 
            Diet::setAppResponse($diets,$app_response);
           // Diet::sendDietOnVediqueDiet($code , $diets , $email);
        }

        if($patient->sms && $patient->lead->country == 'IN')
        {
            foreach ($diets as $diet) {

                $sms_response = Diet::sendSMS($diet);

                if ($sms_response) {
                    $message .= '<li>Diet SMS Sent</li>';
                    $status = 'success';
                    //Diet::setSMSResponse($diet, $sms_response);
                }
            }                
        }
        if ($patient->email && trim($patient->lead->email) <> '') {
       
            $body = Diet::emailHeader($patient);
        
            foreach ($diets as $diet) {
                $body .= Diet::emailBody($diet);
            }                
            //dd($body);
            $body .= Diet::emailFooter();
            
            if(Diet::email($patient, $body)) {
                $message .= '<li>Diet Email Sent</li>';
                $status = 'success';
                Diet::setEmailStatus($diets);
            }
        }
        else {
            $message .= '<li>Email does not exist for '.$patient->lead->name.'</li>';
            $status = 'error';
        }

        $data = array(
            'message'       =>  $message,
            'status'        =>  $status
        );

        return $data;
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

    public static function herbsHeader($patient)
    {
        $body = Storage::get('templates/diets/herbsheader.php'); 
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
        $hrbs = Diet::nl2list($diet->herbs);
        //$hrbs =  '<li>' . str_replace("\n", '</li><li>', $diet->herbs) . '</li>';
        //$hrbs = str_replace('\r\n', '<br>', $hrbs);
        $body = str_replace('$herbs', $hrbs, $body);
        $body = str_replace('$remarks', $diet->rem_dev, $body);

        return $body;
    }

    public static function fabemailBody($diet)
    {
        $patient = Patient::find($diet->patient_id);

        $body = Storage::get('templates/diets/fabcontent.php'); 
        $body = str_replace('$date', date('D, jS M, Y', strtotime($diet->date_assign)), $body); 
        $body = str_replace('$nutritionist', $patient->nutritionist, $body); 
        //$body = str_replace('$early_morning', $diet->early_morning, $body);
        $body = str_replace('$breakfast', $diet->breakfast, $body);
        $body = str_replace('$mid_morning', $diet->mid_morning, $body);
        $body = str_replace('$lunch', $diet->lunch, $body);
        $body = str_replace('$evening', $diet->evening, $body);
        $body = str_replace('$dinner', $diet->dinner, $body);
        //$hrbs = Diet::nl2list($diet->herbs);
        //$hrbs =  '<li>' . str_replace("\n", '</li><li>', $diet->herbs) . '</li>';
        //$hrbs = str_replace('\r\n', '<br>', $hrbs);
        //$body = str_replace('$herbs', $hrbs, $body);
        $body = str_replace('$remarks', $diet->rem_dev, $body);

        return $body;
    }

    public static function herbsBody($herbs ,$doctor)
    {
        

        $body = Storage::get('templates/diets/herbs.php'); 
        $body = str_replace('$Doctors', $doctor, $body);
        $hrbs = Diet::nl2list($herbs);
        $body = str_replace('$herbs', $hrbs, $body);

        return $body;
    }

    public static function nl2list($str, $tag = 'ol')
    {
        if(strpos($str, "\n"))
            $herbs = explode("\n", $str);
        else
            $herbs = explode(" + ", $str);

        if($tag=='ol')
        {
            $newstring = '<' . $tag . ' style="padding: 0px">';

            foreach ($herbs as $herb) {
                $newstring .= "<li>" . $herb . "</li>";
            }
        }
        else
        {   $newstring = "";
            foreach ($herbs as $herb) {
                $newstring .= $herb . "<hr style='margin: 4px;border-top: 1px solid #999;width: 100%'>";
            }

        }

        return $newstring . '</' . $tag . '>';
    }

    public static function emailFooter()
    {
        return Storage::get('templates/diets/footer.php');  
    }

    public static function email($patient, $body)
    {
        Mail::send('templates.emails.empty', array('body' => $body), function($message) use ($patient)
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

    public static function herbsEmail($patient, $body)
    {
        Mail::send('templates.emails.empty', array('body' => $body), function($message) use ($patient)
        {
            $message->to($patient->lead->email, $patient->lead->name)
                ->bcc("diet@nutrihealthsystems.co.in")
                ->subject("Herbs prescription - ".$patient->lead->name." - ".date('D, jS M, Y H:i:s'))
                ->from('service@nutrihealthsystems.com', 'Nutri-Health Systems');
                //->setBody($body);
            
            //Add CC
            if (trim($patient->lead->email_alt) <> '') {
                $message->cc($patient->lead->email_alt, $patient->lead->name);
            }
        });

        return true;
    }

    /*private static function setSMSResponse($diet, $sms_response)
    {
        $diet->sms_response = $sms_response;
        $diet->save();
    }*/

    private static function setEmailStatus($diets)
    {   
        foreach ($diets as $diet) {
            $diet->email = true;
            $diet->save();
        }            
    }

    private static function setAppResponse($diets,$response)
    {   
        $app_response = 0;
        $status = $response->getStatusCode();
        if($status ==200)
            $app_response = 1;
        foreach($diets as $diet){
            $diet->sms_response = $status;
            $diet->save();
        }
    }

    public static function sendDietOnVediqueDiet($code , $diets , $email)
    {
            $client = new Client();
            $app_response = $client->request('POST', 'https://myapplication-47c35.appspot.com/api/user/storeDiets', [
                    'form_params' => [
                    'api_token'=>$code,
                    'diet' => json_encode($diets),
                    'email' => json_encode($email)]
                    ]);

            return "Diet sended on Vedique Diet APP";
    }

    private static function AddVediqueDiet($diets, $email)
    {

        
        $email = $email;
        Diet::deletDiet($email);
        //dd($diets);
        foreach ($diets as $diet) {

            $vdiet = new VediqueDietPaidDiet;
            $vdiet->date_assign = date('Y-m-d', strtotime($diet->date_assign));
            $vdiet->email = $email;
            $vdiet->breakfast = trim($diet->breakfast);
            $vdiet->mid_morning = trim($diet->mid_morning);
            $vdiet->lunch = trim($diet->lunch);
            $vdiet->evening = trim($diet->evening);
            $vdiet->dinner = trim($diet->dinner);
            $vdiet->herbs   = $diet->herbs;
            $vdiet->date =  date("Y/m/d") ;
            $vdiet->save();
        }
       

        return "master Diet Added";
       
    }

    public static function deletDiet($email)
    {
        $dd = VediqueDietPaidDiet::where('email' , $email)
                    ->where('date_assign' , '!=' , date("Y/m/d"))
                    ->delete();
    }
}
