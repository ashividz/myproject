<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Support\SMS;
use App\Models\Email;
use App\Models\EmailTemplate;

use App\Support\Helper;

use Mail;

class SendProductReminderEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $lead;
    protected $products;
    protected $emailTemplateId;
    protected $productKitId;
    protected $herbs;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($lead,$products,$emailTemplateId,$productKitId,$herbs)
    {
        $this->lead            = $lead;
        $this->products        = $products;
        $this->emailTemplateId = $emailTemplateId;
        $this->productKitId    = $productKitId;
        $this->herbs           = $herbs;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $template =  EmailTemplate::find($this->emailTemplateId);
        $data = array(
                'products'     => $this->products,
                'lead'         => $this->lead,
                'productKitId' => $this->productKitId,
                'herbs'        => $this->herbs,
            );
        $body     =  Helper::renderView($template->email,$data);
        $this->sendEmail($this->lead,$template,$body);
    }

    public function sendEmail($lead,$template,$body)
    {
        if (filter_var(!$lead->email, FILTER_VALIDATE_EMAIL) && !filter_var($lead->email_alt, FILTER_VALIDATE_EMAIL))
            return false;
        Mail::send([], [], function($message) use ($lead, $template,$body)
        {
            $from = isset($template->from) ? $template->from : 'sales@nutrihealthsystems.com';
            
            if (filter_var($lead->email, FILTER_VALIDATE_EMAIL)) {
                $message->to($lead->email, $lead->name);
            }
            if (filter_var($lead->email_alt, FILTER_VALIDATE_EMAIL)) {
                $message->to($lead->email_alt, $lead->name);
            }
            
            $message->subject($template->subject);
            $message->from($from, 'Nutri-Health Systems' );
            
            $message->setBody($body, 'text/html');

            //Add attachments
            foreach ($template->attachments as $attachment) {
                $message->attachData($attachment->file, $attachment->name);
            }
        });

        $sms = null;
        
        if ($lead->country == "IN" && $template->sms <> "" && Helper::isIndianNumber($lead->mobile)) {
            $sms = $this->sendSMS($lead, $template);
        }

        $email = new Email();
        $email->user_id = 0;
        $email->lead_id = $lead->id;
        $email->email = $body;
        $email->template_id = $this->emailTemplateId;
        $email->sms_response = $sms;
        $email->save();

    }

    private function sendSMS($lead, $template) 
    {
        $sms = new SMS();
        return $sms->send($lead->mobile, $template->sms);
    }
}