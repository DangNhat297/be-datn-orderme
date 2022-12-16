<?php

namespace App\Events\Chat;

use App\Models\Room;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $newMessage;

    public function __construct($newMessage)
    {
        $this->newMessage = $newMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['chat-channel'];
    }

    public function broadcastAs()
    {
        return 'chat';
    }

    public function broadcastWith()
    {
        $user = User::where('phone', $this->newMessage->sender_phone)->first();
        $room = Room::where('id', $this->newMessage->room_id)->first();
        return [
            'id' => $this->newMessage->id,
            'content' => $this->newMessage->content,
            'created_at' => $this->newMessage->created_at,
            'room_id' => $this->newMessage->room_id,
            'room' => $room,
            'sender' => $user
        ];
    }
}
