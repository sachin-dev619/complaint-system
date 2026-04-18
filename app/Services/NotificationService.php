<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public static function create($userId = null, $title, $description = null, $type = null)
    {
        return Notification::create([
            'user_id' => $userId, // null allowed for admin notifications
            'title' => $title,
            'description' => $description,
            'type' => $type
        ]);
    }
}