<?php
namespace App\Http\Controllers\Admin;

use App\Models\Notification;
use App\Models\NotificationUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function fetchNotification()
    {
        $userId = Auth::id();

        $notifications = NotificationUser::with('notification')
            ->where('user_id', $userId)
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->notification->id,
                    'title' => $item->notification->title,
                    'message' => $item->notification->message,
                    'link' => $item->notification->link,
                    'is_read' => $item->is_read,
                    'time_ago' => $item->created_at->diffForHumans(),
                ];
            });

        $unreadCount = NotificationUser::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }


    public function markAsRead($id)
    {
        $userId = Auth::id();

        $notificationUser = NotificationUser::where('user_id', $userId)
            ->where('notification_id', $id)
            ->first();

        if ($notificationUser) {
            $notificationUser->is_read = true;
            $notificationUser->save();

            $link = $notificationUser->notification?->link ?? '#';

            return response()->json([
                'success' => true,
                'link' => $link,
            ]);
        }

        return response()->json([
            'success' => false,
            'link' => '#',
        ]);
    }

}
