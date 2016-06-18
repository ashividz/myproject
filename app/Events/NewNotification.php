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
    public $recipient;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($recipient)
    {
        $this->recipient = $recipient;

        $this->notifications = Notification::
                                whereHas('recipients', function($q) use($recipient) {
                                    $q->where('recipient_id', $recipient);
                                })
                                ->get();
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['user'. $this->recipient];
    }
}