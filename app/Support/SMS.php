<?php

namespace App\Support;

class SMS {

	private $username;
	private $password;
	private $senderid;
	private $url;
	private $type;


	public function __construct($promo = FALSE)
	{
        if ($promo) 
        {
            
            $this->username = env('PROMO_SMS_USERNAME');
            $this->password     = env('PROMO_SMS_PASSWORD');
            $this->senderid = env('PROMO_SMS_SENDERID');
            $this->url      = env('PROMO_SMS_URL');
            $this->type     = env('PROMO_SMS_TYPE');
        }
        else
        {
            $this->username = env('TRANS_SMS_USERNAME');
            $this->password     = env('TRANS_SMS_PASSWORD');
            $this->senderid = env('TRANS_SMS_SENDERID');
            $this->url      = env('TRANS_SMS_URL');
            $this->type     = env('TRANS_SMS_TYPE');
        }
        
        $this->url = $this->url . "username=" . urlencode($this->username) . "&pass=" . urlencode($this->password) . "&senderid=" . urlencode($this->senderid) . "&response=Y";
	}

	public function send($mobile, $message)
    {
        $this->url = $this->url . "&dest_mobileno=" . urlencode($mobile) . "&message=" . urlencode($message);
        $ch = curl_init();
        $timeout = 30;
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    } 
}