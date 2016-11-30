<?php

namespace App\Http;

class Flash {

	public function create($title, $message, $type, $key = "flash_message")
	{

		session()->flash($key, [
			'title'		=>	$title,
			'message'	=>	$message,
			'type'		=>	$type

		]);
	}

	public function info($title, $message)
	{
		$this->create($title, $message, 'info');
	}

	public function success($title, $message)
	{
		$this->create($title, $message, 'success');
	}

	public function error($title, $message)
	{
		$this->create($title, $message, 'danger');
	}

	public function overlay($title, $message)
	{
		$this->create($title, $message, 'info', 'flash_message_overlay');
	}

}