<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BroadCaseEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $msg, $id;
    /**
     * Create a new event instance.
     */
    public function __construct($msg, $id = 1)
    {
        $this->msg = $msg;
        $this->id = $id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return ['test-channel'];
    }

    public function broadcastAs()
    {
        return 'new-message.'.$this->id;
    }

    public function broadcastWith(){
        return [
            'name' => 'Dinesh Baidya',
            'msg' => $this->msg
        ];
    }
}
