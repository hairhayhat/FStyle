<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\User;

class NotificationService
{
    /**
     * Tạo notification cho tất cả admin
     *
     * @param string $title
     * @param string $message
     * @param string|null $link
     * @param int|null $relatedId
     * @return Notification
     */
    public function notifyAdmins(string $title, string $message, string $link = null, int $relatedId = null)
    {
        $notification = Notification::create([
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'type' => 'user_to_admin',
            'related_id' => $relatedId,
        ]);

        $admin = User::where('role_id', 1)->first();

        NotificationUser::create([
            'notification_id' => $notification->id,
            'user_id' => $admin->id,
            'is_read' => false,
            'assigned_admin_id' => null,
        ]);

        return $notification;
    }

    /**
     * Tạo notification cá nhân cho user (khách hàng)
     *
     * @param User $user
     * @param string $title
     * @param string $message
     * @param string|null $link
     * @param string $type
     * @param int|null $relatedId
     * @return Notification
     */
    public function notifyUser(User $user, string $title, string $message, string $type, string $link = null, int $relatedId = null)
    {
        $notification = Notification::create([
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'type' => $type,
            'related_id' => $relatedId,
        ]);

        NotificationUser::create([
            'notification_id' => $notification->id,
            'user_id' => $user->id,
            'is_read' => false,
            'assigned_admin_id' => null,
        ]);

        return $notification;
    }


    public function markAsRead(int $notificationId, int $userId)
    {
        $notifUser = NotificationUser::where('notification_id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if ($notifUser) {
            $notifUser->is_read = true;
            $notifUser->save();
        }
    }

    public function startProcessing(int $notificationId, int $adminId)
    {
        $notifUser = NotificationUser::where('notification_id', $notificationId)
            ->where('user_id', $adminId)
            ->first();

        if ($notifUser && $notifUser->assigned_admin_id === null) {
            $notifUser->assigned_admin_id = $adminId;
            $notifUser->processed_at = now();
            $notifUser->save();
            return true;
        }

        return false;
    }
}
