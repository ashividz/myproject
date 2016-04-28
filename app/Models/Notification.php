<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon;

class Notification extends Model
{
    public function getCreatedAtAttribute($created_at)
    {
        $created_at = new Carbon($created_at);
        return $created_at->diffForHumans();
    }

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
