<?php

namespace App\Events;

use App\Models\ChatMessages;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

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
        return [
            new PrivateChannel("chat"),
        ];
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
                'text' => $this->chatMessage->message,
                'status' => $this->chatMessage->status,
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
