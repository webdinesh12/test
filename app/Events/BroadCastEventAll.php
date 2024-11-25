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

class BroadCastEventAll implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private $msg;
    /**
     * Create a new event instance.
     */
    public function __construct($msg)
    {
        $this->msg = $msg;
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
        return 'new-message';
    }

    public function broadcastWith(){
        $count = 0;
        if(session()->has('count')){
            $count = session()->get('count') + 1;
        }
        session()->put('count', $count);
        return [
            'name' => 'Dinesh Baidya',
            'msg' => 'This event is triggered from Event All - '.$count
        ];
    }
}