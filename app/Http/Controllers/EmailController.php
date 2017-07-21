<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Lead;
use App\Models\Email;
use App\Models\EmailTemplate;
use App\Support\SMS;
use Mail;
use Auth;

class EmailController extends Controller
{

	public function show($id)
	{
		$lead = Lead::find($id);
		$emails = Email::where('lead_id', $id)
					->orderBy('created_at', 'DESC')
					->limit('20')
					->get();

		$templates = EmailTemplate::where('email_template_category_id',1)
					->with('attachments')->get();

		$data = array(
            'menu'          => 'lead',
            'section'       => 'partials.email',
            'lead'			=>	$lead,
            'templates'		=>	$templates,
            'emails'		=>	$emails,
            'i'				=> 	'1'
        );

        return view('home')->with($data);
	}

	public function send(Request $request, $id)
	{
		//dd($request);
		$sms = NULL;
		$lead = Lead::find($id);
		$template = EmailTemplate::find($request->template_id);
		$status = '';

		if ($lead->country == "IN" && $template->sms <> "") {
			$sms = $this->sendSMS($lead, $template);
			$status .= 'SMS Sent';
		}

		if (trim($lead->email) == '') {
			return redirect('lead/'.$id.'/email')->with('status', $status);	# code...
		}		

		if($template->email <> "")
		{
			$this->sendEmail($lead, $template);
		}

		//Save
		$email = new Email();
		$email->user_id = Auth::user()->id;
		$email->lead_id = $lead->id;
		$email->template_id = $request->template_id;
		$email->email = str_replace('$client', $lead->name, $template->email);
		$email->sms_response = $sms;

		$email->save();

		$status .= 'Email Sent';
		
		return redirect('lead/'.$id.'/email')->with('status', $status);
	}

	private function sendSMS($lead, $template) 
	{
		$sms = new SMS();
		return $sms->send($lead->mobile, $template->sms);
	}

	private function sendEmail($lead, $template) 
	{
		$body = $template->email;
		$body = str_replace('$customer', $lead->name, $body);
		$body = str_replace('$client', $lead->name, $body);
		$body = str_replace('$cre', Auth::user()->employee->name, $body);

		if (Auth::user()->hasRole('nutritionist')) {
			$body = str_replace('$nutritionist', 'Nutritionist ' . Auth::user()->employee->name, $body);
		}
		else
		{
			$body = str_replace('$nutritionist', 'Nutritionist', $body);
		}
		
		Mail::send([], [], function($message) use ($body, $lead, $template)
		{
		    $from = isset($template->from) ? $template->from : 'sales@nutrihealthsystems.com';
		    
		    $message->to($lead->email, $lead->name)
		    ->subject($template->subject)
		    ->from($from, 'Nutri-Health Systems' );
		    
		    //Add CC
		    if (trim($lead->email_alt) <> '') {
		    	$message->cc($lead->email_alt, $name = null);
		    }

		    $message->setBody($body, 'text/html');

			//Add attachments
			foreach ($template->attachments as $attachment) {
				$message->attachData($attachment->file, $attachment->name);
			}
		});
	}
}