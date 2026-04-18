<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // 👨‍🎓 Student notifications
    public function index()
    {
        return Notification::where('user_id', auth()->id())
            ->where('type', 'student')
            ->latest()
            ->get();
    }

    // 👨‍💼 Admin notifications
    public function adminNotifications()
    {
        return Notification::where('type', 'admin')
            ->latest()
            ->get();
    }

    // mark single read
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($notification) {
            $notification->update(['is_read' => true]);
        }

        return response()->json(['message' => 'Marked as read']);
    }

    // mark all read
    public function markAllRead()
    {
        Notification::where('user_id', auth()->id())
            ->update(['is_read' => true]);

        return response()->json(['message' => 'All marked as read']);
    }
}