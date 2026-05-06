<?php

namespace App\Livewire\Backend;

use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class AdminSubjectIndex extends Component
{
    use WithPagination;

    // ── Filters ─────────────────────────────────────────────
    public string $search        = '';
    public string $filterClassId = '';

    // ── Modal state ─────────────────────────────────────────
    public bool $showModal       = false;
    public bool $showDeleteModal = false;

    // ── Form fields ─────────────────────────────────────────
    public ?int $editingId  = null;
    public ?int $deletingId = null;

    #[Rule('required|exists:classes,id')]
    public string $class_id = '';

    #[Rule('nullable|exists:teachers,id')]
    public string $teacher_id = '';

    #[Rule('required|string|max:100')]
    public string $name = '';

    #[Rule('nullable|string|max:50')]
    public string $code = '';

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
        $allClasses  = ClassModel::orderBy('name')->get();
        $allTeachers = Teacher::active()->orderBy('name')->get();

        $subjects = Subject::query()
            ->with(['class', 'teacher'])
            ->when($this->search, fn ($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
            )
            ->when($this->filterClassId, fn ($q) =>
                $q->where('class_id', $this->filterClassId)
            )
            ->latest()
            ->paginate(10);

        return view('livewire.pages.backend.admin-subject-index', compact('subjects', 'allClasses', 'allTeachers'))
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
        $subject = Subject::findOrFail($id);
        $this->editingId   = $subject->id;
        $this->class_id    = (string) $subject->class_id;
        $this->teacher_id  = (string) ($subject->teacher_id ?? '');
        $this->name        = $subject->name;
        $this->code        = $subject->code ?? '';
        $this->showModal   = true;
    }

    // ── Save (create or update) ──────────────────────────────
    public function save(): void
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 403);
        $this->validate();

        $data = [
            'class_id'   => $this->class_id,
            'teacher_id' => $this->teacher_id ?: null,
            'name'       => $this->name,
            'code'       => $this->code ?: null,
        ];

        if ($this->editingId) {
            Subject::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Subject updated successfully.');
        } else {
            Subject::create($data);
            session()->flash('success', 'Subject created successfully.');
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
            Subject::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'Subject deleted successfully.');
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
        $this->class_id   = '';
        $this->teacher_id = '';
        $this->name       = '';
        $this->code       = '';
        $this->editingId  = null;
        $this->resetValidation();
    }
}
