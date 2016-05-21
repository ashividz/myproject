<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Message;
use App\Models\MessageRecipient;
use App\Models\User;
use App\Models\Employee;
use Auth;
use DB;
use Carbon;

class MessageRecipientController extends Controller
{
    public function destroy(Request $request)
    {
        $recipient = MessageRecipient::find($request->id);

        if (isset($recipient->id)) {
            $recipient->delete();
            if($recipient->message->recipients->isEmpty()) {
                $recipient->message->delete();
            }
            return 'Recipient deleted';
        }
        
        return 'Recipient not found';
    }
}