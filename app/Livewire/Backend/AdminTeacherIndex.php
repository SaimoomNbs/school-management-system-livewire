<?php

namespace App\Livewire\Backend;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AdminTeacherIndex extends Component
{
    use WithPagination;

    // ── Filters ──────────────────────────────────────────────
    public string $search = '';

    // ── Delete modal ─────────────────────────────────────────
    public bool $showDeleteModal = false;
    public ?int  $deletingId     = null;

    // ── Lifecycle ────────────────────────────────────────────
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ── Render ───────────────────────────────────────────────
    public function render()
    {
        $teachers = Teacher::query()
            ->when($this->search, fn ($q) =>
                $q->where('name',       'like', '%' . $this->search . '%')
                  ->orWhere('phone',      'like', '%' . $this->search . '%')
                  ->orWhere('teacher_id', 'like', '%' . $this->search . '%')
                  ->orWhere('email',      'like', '%' . $this->search . '%')
            )
            ->latest()
            ->paginate(10);

        return view('livewire.pages.backend.admin-teacher-index', compact('teachers'))
            ->layout('layouts.app');
    }

    // ── Confirm delete ───────────────────────────────────────
    public function confirmDelete(int $id): void
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 403);
        $this->deletingId      = $id;
        $this->showDeleteModal = true;
    }

    // ── Delete teacher + linked user ─────────────────────────
    public function delete(): void
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 403);

        if ($this->deletingId) {
            $teacher = Teacher::findOrFail($this->deletingId);

            DB::transaction(function () use ($teacher) {
                $userId = $teacher->user_id;
                $teacher->delete();
                if ($userId) {
                    User::find($userId)?->delete();
                }
            });

            session()->flash('success', 'Teacher and linked account deleted successfully.');
        }

        $this->showDeleteModal = false;
        $this->deletingId      = null;
        $this->resetPage();
    }

    // ── Helpers ──────────────────────────────────────────────
    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId      = null;
    }
}
