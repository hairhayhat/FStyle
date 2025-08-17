<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function notifyOrderToAdmin(string $title, string $message, string $link = null)
    {
        $adminUsers = User::where('role_id', 1)->get();

        foreach ($adminUsers as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => $title,
                'message' => $message,
                'link' => $link,
                'is_read' => false,
            ]);
        }
    }
}
