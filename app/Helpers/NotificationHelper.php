<?php

namespace App\Helpers;

use App\Models\Notification;

class NotificationHelper
{
    public static function send(
        string $type,
        string $title,
        string $message,
        string $icon = 'bell',
        string $color = 'primary'
    ) {
        Notification::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'color' => $color,
            'user_id' => auth()->id(),
            'is_read' => false,
        ]);
    }
}
