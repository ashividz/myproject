<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class Notification extends Model
{
    public static function getUnreadNotifications()
    {
        return Notification::where('user_id', Auth::id())
                //->where('is_read', '')
                ->get();
    }

    public static function getUnreadNotificationCount()
    {
        return Notification::where('user_id', Auth::id())
                //->where('is_read', '')
                ->count();
    }
}
