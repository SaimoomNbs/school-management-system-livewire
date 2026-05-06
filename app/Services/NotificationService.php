<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Send a notification
     *
     * @param string $type
     * @param string $title
     * @param string $message
     * @param string $notifiable_type (e.g., 'App\Models\Student', 'App\Models\User')
     * @param int $notifiable_id
     * @param string $channel
     */
    public static function send($type, $title, $message, $notifiable_type, $notifiable_id, $channel = 'app')
    {
        DB::table('notifications')->insert([
            'type'            => $type,
            'title'           => $title,
            'message'         => $message,
            'notifiable_type' => $notifiable_type,
            'notifiable_id'   => $notifiable_id,
            'channel'         => $channel,
            'is_read'         => false,
            'created_at'      => Carbon::now(),
            'updated_at'      => Carbon::now(),
        ]);
    }
}
