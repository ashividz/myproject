<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Message;
use App\Models\MessageRecipient;
use App\Models\User;
use Auth;
use DB;

class MessageController extends Controller
{
	private $user;


	public function __construct()
	{
		$this->user = Auth::user()->employee->name;
	}

	public function getUnreadMessageCount()
	{
		$user = $this->user;

		return Message::join('message_recipient as mr', 'mr.message_id', '=', 'messages.id')
					->where('mr.name', $user)
					->whereNull('read_at')
					->count();
	}

	public function inbox()
	{
		
		$data = array(
            'menu'          =>  'message',
            'section'       =>  'inbox'
        );

        return view('home')->with($data);
	}

	/* Report messages*/
	public function messages()
	{
		$messages =  Message::with('recipients')
					->orderBy('id', 'desc')
					->limit('100')
					->get();
		
		$data = array(
            'menu'          =>  'reports',
            'section'       =>  'messages',
            'messages'		=>	$messages
        );

        return view('home')->with($data);
	}

	public function getMessages()
	{
		$user = $this->user;

		return  Message::select('messages.*', 'mr.read_at')
					->with('lead')
					->join('message_recipient as mr', 'mr.message_id', '=', 'messages.id')
					->where('mr.name', $user)
					->orderBy('id', 'desc')
					->limit('100')
					->get();
	}

	public function toggle(Request $request)
	{
		$message = MessageRecipient::where('message_id', $request->id)
					->where('name', $this->user)
					->first();

		$message->read_at = $message->read_at == null ? date('Y-m-d H:i:s') : null;
		$message->save();
	}

	public function send(Request $request)
	{
		//dd($request);
		$message = new Message;
		$message->from = $this->user;
		//$message->to = $request->to;
		$message->subject = $request->subject;
		$message->body = $request->body;
		$message->type_id = $request->type_id;
		$message->lead_id = $request->lead_id;
		$message->save();

		$receiver = MessageRecipient::saveRecipients($message, $request->recipients);

		return "Message sent successfully";
	}

	public function compose()
	{
		$users = User::getUsers();

		$data = array(
            'menu'          =>  'message',
            'section'       =>  'compose',
            'users'			=>	$users
        );

        return view('home')->with($data);
	}

	public function outbox()
	{
		
		$messages = Message::where('from', $this->user)
					->orderBy('id', 'desc')
					->limit('100')
					->get();
		
		$data = array(
            'menu'          =>  'message',
            'section'       =>  'outbox',
            'messages'		=>	$messages
        );

        return view('home')->with($data);
	}
}