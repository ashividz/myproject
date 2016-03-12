<?php

namespace App\Support;

class Email {

	private $username;
	private $password;
	private $senderid;
	private $url;
	private $type;

	public function __construct($bulk = FALSE)
	{
        if ($bulk) 
        {
        	$this->host = "smtpcorp.com";
	        $this->port = "2525";
	        $this->username = "help@drshikha.com";
	        $this->password = "help@100#";
        }
        else
        {
            $this->host = "smtpcorp.com";
	        $this->port = "2525";
	        $this->username = "help@drshikha.com";
	        $this->password = "help@100#";
        }
	}
}