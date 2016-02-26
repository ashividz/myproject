<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Auth;

class MessageRecipient extends Model
{
	protected $table = "message_recipient";
	public $timestamps = false;

	public function message()
	{
		$this->hasOne(Message::class);
	}

	public static function saveRecipients($message, $recipients)
	{
		$count = count($recipients);

		for ($i=0; $i < $count; $i++) {

			//$user = User::find($recipients[$i]);

			$r = new MessageRecipient;
			$r->message_id = $message->id;
			$r->name = $recipients[$i];
			//$r->name = $user->employee->name;
			$r->save();
		}
	}
}