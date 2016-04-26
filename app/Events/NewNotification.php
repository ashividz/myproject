<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Models\Notification;
use Auth;

class NewNotification extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $notifications; 
    public $unreadNotificationCount;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->notifications = Notification::getUnreadNotifications();
        $this->unreadNotificationCount = Notification::getUnreadNotificationCount();
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['user'. Auth::id()];
    }
}