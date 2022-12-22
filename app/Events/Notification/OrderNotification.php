<?php

namespace App\Events\Notification;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public function __construct()
    {

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['order-channel'];
    }

    public function broadcastAs()
    {
        return 'order';
    }

    public function broadcastWith()
    {
        return [
            'data' => $data,
            'notifyNotSeen' => $notifyNotSeen
        ];
    }
}
