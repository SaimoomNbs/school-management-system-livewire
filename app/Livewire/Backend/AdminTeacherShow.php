<?php

namespace App\Livewire\Backend;

use App\Models\Teacher;
use Livewire\Component;

class AdminTeacherShow extends Component
{
    // ── Store only the ID to avoid serialization issues ──────
    public int $teacherId;

    // ── Mount ────────────────────────────────────────────────
    public function mount(Teacher $teacher): void
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 403);
        $this->teacherId = $teacher->id;
    }

    // ── Render ───────────────────────────────────────────────
    public function render()
    {
        $teacher = Teacher::with(['user', 'subjects.class'])
            ->findOrFail($this->teacherId);

        return view('livewire.pages.backend.admin-teacher-show', compact('teacher'))
            ->layout('layouts.app');
    }
}
