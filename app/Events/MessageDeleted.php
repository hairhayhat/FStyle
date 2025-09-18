<?php

namespace App\Events;

use App\Models\ChatMessages;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\Channel;

class MessageDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatMessage;

    public function __construct(ChatMessages $chatMessage)
    {
        $this->chatMessage = $chatMessage;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel("chat." . $this->chatMessage->receiver_id),
            new Channel("chat." . $this->chatMessage->sender_id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'message' => [
                'id' => $this->chatMessage->id,
                'status' => $this->chatMessage->status,
            ],
        ];
    }
}
