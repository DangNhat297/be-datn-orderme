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


    public function __construct(
        protected $newData,
        protected $user,
        protected $notification
    )
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
            'id' => $this->newData->id,
            'isSeen' => $this->newData->isSeen,
            'notification_id' => $this->newData->notification_id,
            'recipient_id' => $this->newData->recipient_id,
            'created_at' => $this->newData->created_at,
            'updated_at' => $this->newData->updated_at,
            'user' => $this->user,
            'notification' => $this->notification
        ];
    }


}
