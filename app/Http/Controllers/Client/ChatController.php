<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatMessages;
use App\Models\ChatMessagesMedia;
use App\Events\MessageSent;
use App\Models\User;

class ChatController extends Controller
{
    public function index(User $user)
    {
        $messages = ChatMessages::with(['sender', 'receiver'])
            ->whereIn('sender_id', [auth()->id(), $user->id])
            ->whereIn('receiver_id', [auth()->id(), $user->id])
            ->get();
        return response()->json($messages);
    }

    public function store(User $user, Request $request)
    {
        $chatMessage = ChatMessages::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
            'status' => 0,
        ]);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('chat_media', 'public');
                ChatMessagesMedia::create([
                    'message_id' => $chatMessage->id,
                    'path' => $path,
                    'type' => $file->getClientMimeType(),
                ]);
            }
        }

        broadcast(new MessageSent($user, $chatMessage))->toOthers();

        return response()->json($chatMessage->load('media'));
    }
}
