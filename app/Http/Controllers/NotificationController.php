<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function notificationReadAll()
    {
        $user = Auth::user();
        if ($user->unreadNotifications->isNotEmpty()) {
            $user->unreadNotifications->markAsRead();
            return response()->json([
                'message' => 'All notifications marked as read.',
                'status' => true
            ]);
        }
        return response()->json([
            'message' => 'No unread notifications found.',
            'status' => false
        ]);
    }

    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications;
        $notificationCount = $notifications->count();

        return response()->json([
            'success' => true,
            'count' => $notificationCount,
            'data' => $notifications,
        ]);
    }
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification not found.',
        ], 404);
    }
}
