<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Models\Message;

class NewMessage extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $count;
    private $employee;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($employee)
    {
        $this->employee = $employee;

        $this->count = Message::join('message_recipient as r', 'r.message_id', '=', 'messages.id')
                    ->where('r.name', 'like', $employee->name)
                    ->whereNull('read_at')
                    ->count();
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    // public function broadcastOn()
    // {
    //     return ['user'. $this->employee->user->id];
    // }
}
