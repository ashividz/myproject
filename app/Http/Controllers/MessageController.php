<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Events\NewMessage;

use App\Models\Message;
use App\Models\MessageRecipient;
use App\Models\User;
use App\Models\Employee;
use Auth;
use DB;
use Carbon;

class MessageController extends Controller
{
	private $user;


	public function __construct()
	{
		$this->user = Auth::user()->employee->name;
	}

    public function index()
    {
        $data = array(
            'menu'          =>  'admin',
            'section'       =>  'messages.index'
        );
        return view('home')->with($data);
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
					->limit('500')
					->get();
		
		$data = array(
            'menu'          =>  'reports',
            'section'       =>  'messages',
            'messages'		=>	$messages
        );

        return view('home')->with($data);
	}

	public function get(Request $request)
	{
		$user = $this->user;

		$messages =   Message::select('messages.*', 'mr.read_at', 'mr.action_at')
					->with('lead')
					->join('message_recipient as mr', 'mr.message_id', '=', 'messages.id')
					->where('mr.name', $user);
        
        if ($request->read == 1) {
            $messages->whereNull('read_at');
        }
        
		return $messages->orderBy('id', 'desc')
					->limit('100')
					->get();
	}

    public function getAllMessages(Request $request = null)
    {
        $users = null;//dd($request->filter_unread);

        $start_date = isset($request->start_date) ? Carbon::parse($request->start_date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        $end_date = isset($request->end_date) ? Carbon::parse($request->end_date)->format('Y-m-d 23:59:59') : Carbon::now(); 

        if ($request->role) {
            $users = User::getUsersByRole($request->role);
            $users = $users->pluck('name');
        } else if($request->user) {
             $users = [$request->user];
        }

        $query =  Message::with('recipients')
                    ->with(['lead' => function($q){
                        $q->select('id', 'name');
                    }])
                    ->orderBy('id', 'desc')
                    ->whereBetween('created_at', [$start_date, $end_date]);

        if ($users) {
            $query->whereHas('recipients', function($q) use ($users, $request){
                $q->whereIn('name', $users);
                if ($request->filter_unread == 'true') {
                    $q->whereNull('read_at');
                } 
                if ($request->filter_action == 'true') {
                    $q->whereNull('action_at');
                } 
            });
        }
        
        $messages = $query->limit(500)->get();

        return $messages;
    }

    public function setRead(Request $request)
    {
        $message = MessageRecipient::where('message_id', $request->id)
                    ->where('name', $this->user)
                    ->first();
        if ($message) {
            $message->read_at = $message->read_at == null ? date('Y-m-d H:i:s') : $message->read_at;
            $message->save();
        }
    }

    public function setAction(Request $request)
    {
        $message = MessageRecipient::where('message_id', $request->id)
                    ->where('name', $this->user)
                    ->first();
        if ($message) {
            $message->action_at = $message->action_at == null ? date('Y-m-d H:i:s') : $message->action_at;
            $message->read_at = $message->read_at == null ? date('Y-m-d H:i:s') : $message->read_at;
            $message->save();
        }
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
		//make usernames list unique
        $recipients = array_unique($request->recipients);
        $messageRecipients = array();
        foreach($recipients as $recipient){
            $messageRecipients[] = $recipient;
        }
        //dd($request);
		$message = new Message;
		$message->from = $this->user;
		//$message->to = $request->to;
		$message->subject = $request->subject;
		$message->body = $request->body;
		$message->type_id = $request->type_id;
		$message->lead_id = $request->lead_id;
		$message->save();

		$receiver = MessageRecipient::saveRecipients($message, $messageRecipients);

        //$count = ;

        for ($i=0; $i < count($request->recipients); $i++) {

            $employee = Employee::where('name', $request->recipients[$i])->first(); 
            if ($employee) {
                event(new NewMessage($employee));
            }            
        }
        
		$data = array(
            'message'       => 'Message sent successfully',
            'status'        =>  'success'
        );

        return "Message sent successfully";
        //return back()->with($data);
	}

	public function compose()
	{
		$users = User::getUsersWithEmployee();        

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