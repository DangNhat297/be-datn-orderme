<?php

namespace App\Events\Chat;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatNotiEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $list = [];

    public function __construct($list)
    {
        $this->list = $list;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['get-chat-room-admin'];
    }

    public function broadcastAs()
    {
        return 'chat-room';
    }

    public function broadcastWith()
    {
        $this->list->map(function ($item) {
//            $item['user'] = User::where('phone', $item->user_phone)->first();
            $item['message'] = Chat::with(['sender'])
                ->where('room_id', $item->id)
                ->orderBy('id', 'desc')->first();
            $item['messageNotSeen'] = count(Chat::where('sender_phone', '=', $item->user_phone)
                ->where('room_id', $item->id)
                ->where('isSeen', false)
                ->get());
            return $item;
        });

        return [$this->list];
    }
}
