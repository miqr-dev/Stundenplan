<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
public function index()
{
    $user = Auth::user();
    $notifications = $user->unreadNotifications; 
    return response()->json(['notifications' => $notifications]);
}

    public function markAsRead($notificationId) // Use Notification Id instead
    {
        $user = Auth::user();
        $notification = $user->notifications->find($notificationId);

        if (!$notification) {
            return response()->json(['status' => 'failed', 'message' => 'Notification not found'], 404);
        }

        $notification->markAsRead();
        return response()->json(['status' => 'success']);
    }

}
