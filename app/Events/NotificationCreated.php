<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // ✅ IMPORTANT
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationCreated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $notification;

    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    // ✅ ADMIN CHANNEL (NO user_id problem)
    public function broadcastOn()
    {
        if ($this->notification->type === 'admin') {
            return new PrivateChannel('admin.notifications');
        }

        return new PrivateChannel('user.notifications.' . $this->notification->user_id);
    }

    public function broadcastAs()
    {
        return 'notification.created';
    }

    public function broadcastWith()
    {
        return [
            'notification' => $this->notification
        ];
    }
}