<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Notification;

use Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getUnreadNotifications()
    {
        return Notification::getUnreadNotifications()
    }

    public function getUnreadNotificationCount()
    {
        return Notification::getUnreadNotificationCount();
    }
}
