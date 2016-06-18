<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Notification;

use Auth;
use Carbon;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('notification.index');
    }

    public function get()
    {
        return Notification::with('type.object', 'creator.employee')
                    ->whereHas('recipients', function($q) {
                        $q->where('recipient_id', Auth::id())
                            ->whereNull('read_at');
                    })
                    ->orderBy('id', 'desc')
                    ->get();

    }

    public function read($id)
    {
        $notification = Notification::find($id);

        $notification->recipients()
                    ->where('recipient_id', Auth::id())
                    ->update(['read_at' => Carbon::now()]);
        /*NotificationRecipient::where('notification_id', $id)
                        update(['read_at' => Carbon::now()]);*/

        return $this->get();
    }
}
