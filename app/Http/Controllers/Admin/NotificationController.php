<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function fetchNotification()
    {
        $notifications = Notification::latest()->take(5)->get();

        return response()->json([
            'count' => Notification::count(),
            'notifications' => $notifications,
        ]);
    }
}
