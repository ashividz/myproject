<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\NewNotification;

use Auth;
use Carbon;

class Notification extends Model
{
    public function recipients()
    {
        return $this->hasMany(NotificationRecipient::class);
    }

    public function type()
    {
        return $this->belongsTo(NotificationType::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getCreatedAtAttribute($created_at)
    {
        $created_at = new Carbon($created_at);
        return $created_at->diffForHumans();
    }

    public static function store($type_id, $object, $recipient)
    {
        $notfication = new Notification;
        $notfication->type_id = $type_id;
        //$notfication->message = $message;
        $notfication->object = $object;
        $notfication->created_by = Auth::id();
        $notfication->save();

        $notfication->recipients()->create(['recipient_id' => $recipient]);
        event(new NewNotification($recipient));
    }
    /*
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
    }*/
}
