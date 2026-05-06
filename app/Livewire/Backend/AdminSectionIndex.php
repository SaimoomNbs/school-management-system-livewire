<?php

namespace App\Livewire\Backend;

use App\Models\ClassModel;
use App\Models\Section;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class AdminSectionIndex extends Component
{
    use WithPagination;

    // ── Filters ─────────────────────────────────────────────
    public string $search   = '';
    public string $filterClassId = '';

    // ── Modal state ─────────────────────────────────────────
    public bool $showModal       = false;
    public bool $showDeleteModal = false;

    // ── Form fields ─────────────────────────────────────────
    public ?int $editingId  = null;
    public ?int $deletingId = null;

    #[Rule('required|exists:classes,id')]
    public string $class_id = '';

    #[Rule('required|string|max:100')]
    public string $name = '';

    // ── Lifecycle ────────────────────────────────────────────
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterClassId(): void
    {
        $this->resetPage();
    }

    // ── Render ───────────────────────────────────────────────
    public function render()
    {
        $allClasses = ClassModel::orderBy('name')->get();

        $sections = Section::query()
            ->with('class')
            ->when($this->search, fn ($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
            )
            ->when($this->filterClassId, fn ($q) =>
                $q->where('class_id', $this->filterClassId)
            )
            ->latest()
            ->paginate(10);

        return view('livewire.pages.backend.admin-section-index', compact('sections', 'allClasses'))
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
        $section = Section::findOrFail($id);
        $this->editingId = $section->id;
        $this->class_id  = (string) $section->class_id;
        $this->name      = $section->name;
        $this->showModal = true;
    }

    // ── Save (create or update) ──────────────────────────────
    public function save(): void
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 403);
        $this->validate();

        $data = ['class_id' => $this->class_id, 'name' => $this->name];

        if ($this->editingId) {
            Section::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Section updated successfully.');
        } else {
            Section::create($data);
            session()->flash('success', 'Section created successfully.');
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
            Section::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'Section deleted successfully.');
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
        $this->class_id  = '';
        $this->name      = '';
        $this->editingId = null;
        $this->resetValidation();
    }
}
