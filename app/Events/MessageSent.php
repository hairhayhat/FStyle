<?php

namespace App\Events;

use App\Models\ChatMessages;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Broadcasting\Channel;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user, $chatMessage;

    public function __construct(User $user, ChatMessages $chatMessage)
    {
        $this->user = $user;
        $this->chatMessage = $chatMessage->load('media');
    }

    public function broadcastOn(): array
    {
        return [new Channel("chat." . $this->chatMessage->receiver_id)];
    }

    public function broadcastWith()
    {
        return [
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'message' => [
                'id' => $this->chatMessage->id,
                'sender_id' => $this->chatMessage->sender_id,
                'receiver_id' => $this->chatMessage->receiver_id,
                'message' => $this->chatMessage->message,
                'is_read' => $this->chatMessage->is_read,
                'is_sent' => $this->chatMessage->is_send,
                'created_at' => $this->chatMessage->created_at->toDateTimeString(),
                'media' => $this->chatMessage->media->map(function ($m) {
                    return [
                        'id' => $m->id,
                        'path' => $m->path,
                        'type' => $m->type,
                    ];
                }),
            ],
        ];
    }

}
