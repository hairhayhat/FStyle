<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use App\Events\MessageSent;
use App\Models\ChatMessages;
use Illuminate\Http\Request;
use App\Events\MessageDeleted;
use App\Events\MessageEdited;
use App\Events\MessageReaded;
use App\Models\ChatMessagesMedia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function index(User $user)
    {
        $messages = ChatMessages::with(['sender', 'receiver', 'media'])
            ->whereIn('sender_id', [auth()->id(), $user->id])
            ->whereIn('receiver_id', [auth()->id(), $user->id])
            ->orderBy('created_at', 'asc')
            ->get();

        $unreadMessages = ChatMessages::where('sender_id', $user->id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->get();

        if ($unreadMessages->count()) {
            ChatMessages::whereIn('id', $unreadMessages->pluck('id'))
                ->update(['is_read' => true]);

            foreach ($unreadMessages as $msg) {
                broadcast(new MessageReaded($msg))->toOthers();
            }
        }

        return response()->json($messages);
    }

    public function store(User $user, Request $request)
    {
        $chatMessage = ChatMessages::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
            'is_sent' => true,
            'is_read' => false,
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

        broadcast(new MessageSent(auth()->user(), $chatMessage->load('media')))->toOthers();

        return response()->json($chatMessage->load('media'));
    }

    public function edit(ChatMessages $chatMessage, Request $request)
    {
        $chatMessage->message = $request->message;
        $chatMessage->status = 2;
        $chatMessage->save();

        broadcast(new MessageEdited($chatMessage->load('media')))->toOthers();

        return response()->json([
            'id' => $chatMessage->id,
            'new_message' => $chatMessage->message,
        ]);
    }

    public function destroy(ChatMessages $chatMessage)
    {
        $chatMessage->message = "Tin nhắn đã bị xóa";
        $chatMessage->status = 1;
        $chatMessage->save();

        if ($chatMessage->has('media')) {
            foreach ($chatMessage->media as $file) {
                if (Storage::exists($file->path)) {
                    Storage::delete($file->path);
                }
                $file->delete();
            }
        }

        broadcast(new MessageDeleted($chatMessage))->toOthers();

        return response()->json([
            'message' => 'Xóa tin nhắn thành công.',
            'id' => $chatMessage->id,
            'new_message' => $chatMessage->message,
        ]);

    }

    public function markAsRead(ChatMessages $chatMessage)
    {
        if ($chatMessage->receiver_id === auth()->id() && !$chatMessage->is_read) {
            $chatMessage->update(['is_read' => true]);
            broadcast(new MessageReaded($chatMessage))->toOthers();
        }

        return response()->json([
            'id' => $chatMessage->id,
            'is_read' => $chatMessage->is_read,
        ]);
    }

}
