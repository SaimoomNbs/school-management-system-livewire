<?php

namespace App\Livewire\Backend;

use App\Models\ClassModel;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class AdminStudentIndex extends Component
{
    use WithPagination;

    // ── Filters ──────────────────────────────────────────────
    public string $search = '';

    #[Url(as: 'class_id')]
    public string $filterClassId = '';

    public string $filterSectionId = '';
    public string $filterStatus = '';

    // ── Delete modal ─────────────────────────────────────────
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    // ── Lifecycle ────────────────────────────────────────────
    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    public function updatingFilterClassId(): void
    {
        $this->filterSectionId = '';
        $this->resetPage();
    }
    public function updatingFilterSectionId(): void
    {
        $this->resetPage();
    }
    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    // ── Render ───────────────────────────────────────────────
    public function render()
    {
        $allClasses = ClassModel::orderBy('name')->get();
        $allSections = $this->filterClassId
            ? Section::where('class_id', $this->filterClassId)->orderBy('name')->get()
            : collect();

        $students = Student::query()
            ->with(['class', 'section'])
            ->when(
                $this->search,
                fn($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('student_id', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
            )
            ->when($this->filterClassId, fn($q) => $q->where('class_id', $this->filterClassId))
            ->when($this->filterSectionId, fn($q) => $q->where('section_id', $this->filterSectionId))
            ->when($this->filterStatus !== '', fn($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(15);

        return view(
            'livewire.pages.backend.admin-student-index',
            compact('students', 'allClasses', 'allSections')
        )->layout('layouts.app');
    }

    // ── Delete confirm ───────────────────────────────────────
    public function confirmDelete(int $id): void
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 403);
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    // ── Delete student + user ────────────────────────────────
    public function delete(): void
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 403);

        if ($this->deletingId) {
            $student = Student::findOrFail($this->deletingId);

            DB::transaction(function () use ($student) {
                $userId = $student->user_id;
                $student->delete();
                if ($userId) {
                    User::find($userId)?->delete();
                }
            });

            session()->flash('success', 'Student and linked account deleted successfully.');
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
        $this->resetPage();
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    // ── Export PDF (respects current filters) ────────────────
    public function exportPdf()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'accountant']), 403);

        $students = Student::query()
            ->with(['class', 'section'])
            ->when(
                $this->search,
                fn($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('student_id', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
            )
            ->when($this->filterClassId, fn($q) => $q->where('class_id', $this->filterClassId))
            ->when($this->filterSectionId, fn($q) => $q->where('section_id', $this->filterSectionId))
            ->when($this->filterStatus !== '', fn($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->get();

        $pdf = Pdf::loadView(
            'livewire.pages.backend.pdf.student-list',
            ['students' => $students, 'generatedAt' => now()]
        )->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn() => print ($pdf->output()),
            'students-' . now()->format('Y-m-d') . '.pdf'
        );
    }
}
