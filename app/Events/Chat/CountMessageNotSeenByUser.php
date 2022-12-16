<?php

namespace App\Events\Chat;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CountMessageNotSeenByUser implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $countMessage;
    public $userPhone;

    public function __construct($countMessage, $userPhone)
    {
        $this->countMessage = $countMessage;
        $this->userPhone = $userPhone;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['count-message-not-seen.' . $this->userPhone];
    }

    public function broadcastAs()
    {
        return 'count-msg';
    }

    public function broadcastWith()
    {
        return [
            'phone' => $this->userPhone,
            'total' => $this->countMessage
        ];
    }
}
