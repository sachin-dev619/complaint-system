<?php

use Illuminate\Support\Facades\Broadcast;

// ✅ Admin channel
Broadcast::channel('admin.notifications', function ($user) {
    return $user->role === 'admin';
});

// ✅ User channel
Broadcast::channel('user.notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});