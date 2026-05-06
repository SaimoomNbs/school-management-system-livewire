<?php

namespace App\Livewire\Backend;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SharedNotificationBell extends Component
{
    public function markAsRead($id)
    {
        DB::table('notifications')
            ->where('id', $id)
            ->update([
                'is_read' => true,
                'updated_at' => now(),
                // 'read_at' => now(), // if your migration has read_at
            ]);
    }

    private function getNotificationQuery()
    {
        $user = auth()->user();
        if (!$user) {
            return DB::table('notifications')->where('id', '<', 0);
        }

        $query = DB::table('notifications')->where('channel', 'app');

        if ($user->hasRole('student') && $user->student) {
            $query->where('notifiable_type', 'App\Models\Student')
                  ->where('notifiable_id', $user->student->id);
        } elseif ($user->hasRole('teacher') && $user->teacher) {
            $query->where('notifiable_type', 'App\Models\Teacher')
                  ->where('notifiable_id', $user->teacher->id);
        } else {
            $query->where('notifiable_type', 'App\Models\User')
                  ->where('notifiable_id', $user->id);
        }

        return $query;
    }

    public function markAllAsRead()
    {
        $this->getNotificationQuery()
            ->where('is_read', false)
            ->update(['is_read' => true, 'updated_at' => now()]);
    }

    public function render()
    {
        $notifications = $this->getNotificationQuery()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $unreadCount = $this->getNotificationQuery()
            ->where('is_read', false)
            ->count();

        return view('livewire.pages.backend.shared-notification-bell', compact('notifications', 'unreadCount'));
    }
}