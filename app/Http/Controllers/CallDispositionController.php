<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Support\Helper;
use App\Support\SMS;
use App\Models\CallDisposition;
use App\Models\Lead;
use App\Models\LeadStatus;
use Auth;
use DB;
use Mail;

class CallDispositionController extends Controller
{
    private $daterange;
    public $start_date;
    public $end_date;

    public function __construct()
    {
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/m/01 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');
        
    }

    public function show($clinic, $enquiry_no)
    {
        $dispositions = CallDisposition::where('clinic', '=', $clinic)
                        ->where('enquiry_no', '=', $enquiry_no)
                        ->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  $cre, $start_date, $end_date
     * @return Response
     */
    public function showByCre($cre, $start_date, $end_date)
    {
        $dispositions = CallDisposition::where('cre', '=', $ccre)
                        ->whereBetween('created_at', array($start_date, $end_date))
                        ->get();
        return $dispositions;
    }

    public function viewDispositions()
    {
        $user = Auth::user()->employee->name;

        $dispositions = DB::table('call_dispositions AS cd')
                            ->where('name', $user)
                            ->whereBetween('created_at', $this->start_date, $this->end_date)
                            ->get();
        $data = array(
            'menu'          => "cre",
            'section'       => "dispositions",
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'dispositions'  => $dispositions
        );

        return view('home')->with($data);
    }

    public function saveDisposition(Request $request, $id)
    {
        try {
            $lead = Lead::find($id);

            $disposition = new CallDisposition;

            $disposition->lead_id = $lead->id;           
            $disposition->clinic = $lead->clinic;
            $disposition->enquiry_no = $lead->enquiry_no;
            $disposition->disposition_id = $request->disposition;
            $disposition->name = Auth::user()->employee->name;
            $disposition->remarks = $request->remarks;
            $disposition->callback = trim($request->callback) ? date('Y/m/d H:i:s', strtotime($request->callback)) : Helper::emptyStringToNull($request->callback);        

            $disposition->save();

            //Update Status
            $status = $disposition->getStatusFromDisposition($disposition->disposition_id);
            $leadStatus = new LeadStatus;
            $leadStatus->setStatus($id, $status->status); 
                    

            //Send SMS & Email if Call Not Connected
            
            if ($request->status == 2) {
                $disposition->sms = $this->sendSMS($lead->phone);
                $disposition->email = $this->sendEmail($lead);
                $disposition->save();
            }

            return "Dispositions saved";

        } catch (\Illuminate\Database\QueryException $e) {
            return "error" . $e;
        }
        
    }

    public function sendSMS($mobile)
    {
        if(trim($mobile) == '')
        {
            return false;
        }
        
        $message = $this->getRNRMessage();
        $sms = new SMS();
        return $sms->send($mobile, $message);

    }

    public function sendEmail($lead)
    {
        if(trim($lead->email) == '')
        {
            return false;
        }

        $data = array(
                'customer'  => $lead->name,
                'name'      => Auth::user()->employee->name,                
            );

        Mail::queue('templates.emails.rnr', $data, function($message) use ($lead)
        {
            $from = Auth::user()->hasRole('nutritionist') || Auth::user()->hasRole('service') || Auth::user()->hasRole('doctor') ? 'dietplan@nutrihealthsystems.co.in' : 'sales@nutrihealthsystems.com';
            
            $message->to($lead->email, $lead->name)
            ->subject('Unable to reach you')
            ->from($from, 'Nutri-Health Systems');
            
            //Add CC
            if (trim($lead->email_alt) <> '') {
                $message->cc($lead->email_alt, $name = null);
            }
        });

        return true;

    }

    public function getRNRMessage()
    {
        $caller = Auth::user()->employee->name;
        
        if (Auth::user()->hasRole('service') || Auth::user()->hasRole('nutritionist')) 
        {
            $message = "We were unable to reach you to discuss your health plan at Dr.Shikha's Nutrihealth. Pls call 011-49945900 for " . $caller . ".\n";
            return $message;
        }
        elseif (Auth::user()->hasRole('sales') || Auth::user()->hasRole('cre')) 
        {
            $message = "We were unable to reach you for free counseling on Dr.Shikha's Weight Loss at Home advisory. Pls call 18001036663 for counselor " . $caller . ".\n";
            $message .= "http://goo.gl/hRyWeS" . "\n";
            return $message;
        }
        
        return "We were unable to reach you for counseling on Dr.Shikha's Weight Loss at Home advisory. Pls call 18001036663 for counselor " . $caller . ".\n";
    }
}
