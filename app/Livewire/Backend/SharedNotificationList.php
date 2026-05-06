<?php

namespace App\Livewire\Backend;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class SharedNotificationList extends Component
{
    use WithPagination;

    public string $filterType = '';

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function markAsRead($id)
    {
        DB::table('notifications')->where('id', $id)->update([
            'is_read' => true,
            'updated_at' => now()
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

    public function render()
    {
        $notifications = $this->getNotificationQuery()
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->orderBy('is_read', 'asc') // Unread first
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.pages.backend.shared-notification-list', compact('notifications'))
            ->layout('layouts.app');
    }
}