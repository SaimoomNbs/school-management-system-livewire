<?php

namespace App\Livewire\Backend;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class AdminClassIndex extends Component
{
    use WithPagination;

    // ── Search ──────────────────────────────────────────────
    public string $search = '';

    // ── Modal state ─────────────────────────────────────────
    public bool $showModal     = false;
    public bool $showDeleteModal = false;

    // ── Form fields ─────────────────────────────────────────
    public ?int $editingId  = null;
    public ?int $deletingId = null;

    #[Rule('required|string|max:100')]
    public string $name = '';

    // ── Lifecycle ────────────────────────────────────────────
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ── Computed query ───────────────────────────────────────
    public function render()
    {

        $classes = ClassModel::query()
            ->when($this->search, fn ($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
            )
            ->latest()
            ->paginate(10);

        return view('livewire.pages.backend.admin-class-index', compact('classes'))
            ->layout('layouts.app');
    }

    // ── Open create modal ────────────────────────────────────
    public function openCreate(): void
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 403);
        $this->resetForm();
        $this->editingId = null;
        $this->showModal = true;
    }

    // ── Open edit modal ──────────────────────────────────────
    public function openEdit(int $id): void
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 403);
        $class = ClassModel::findOrFail($id);
        $this->editingId = $class->id;
        $this->name      = $class->name;
        $this->showModal = true;
    }

    // ── Save (create or update) ──────────────────────────────
    public function save(): void
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 403);
        $this->validate();

        if ($this->editingId) {
            ClassModel::findOrFail($this->editingId)->update(['name' => $this->name]);
            session()->flash('success', 'Class updated successfully.');
        } else {
            ClassModel::create(['name' => $this->name]);
            session()->flash('success', 'Class created successfully.');
        }

        $this->closeModal();
        $this->resetPage();
    }

    // ── Confirm delete ───────────────────────────────────────
    public function confirmDelete(int $id): void
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 403);
        $this->deletingId      = $id;
        $this->showDeleteModal = true;
    }

    // ── Delete ───────────────────────────────────────────────
    public function delete(): void
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 403);

        if ($this->deletingId) {
            ClassModel::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'Class deleted successfully.');
        }

        $this->showDeleteModal = false;
        $this->deletingId      = null;
        $this->resetPage();
    }

    // ── Helpers ──────────────────────────────────────────────
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId      = null;
    }

    private function resetForm(): void
    {
        $this->name      = '';
        $this->editingId = null;
        $this->resetValidation();
    }
}
