<?php

namespace App\Livewire\Backend;

use App\Models\ClassModel;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AdminFeeIndex extends Component
{
    use WithPagination;

    // ── Filters ──────────────────────────────────────────────
    public string $search          = '';
    public string $filterClassId   = '';
    public string $filterStudentId = '';
    public string $filterStatus    = '';
    public string $filterMonth     = '';
    public string $filterYear      = '';

    // ── Lifecycle ────────────────────────────────────────────
    public function updatingSearch(): void          { $this->resetPage(); }
    public function updatingFilterClassId(): void   { $this->filterStudentId = ''; $this->resetPage(); }
    public function updatingFilterStudentId(): void { $this->resetPage(); }
    public function updatingFilterStatus(): void    { $this->resetPage(); }
    public function updatingFilterMonth(): void     { $this->resetPage(); }
    public function updatingFilterYear(): void      { $this->resetPage(); }

    // ── Render ───────────────────────────────────────────────
    public function render()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'accountant', 'teacher']), 403);

        $allClasses  = ClassModel::orderBy('name')->get();
        $allStudents = $this->filterClassId
            ? Student::where('class_id', $this->filterClassId)->orderBy('name')->get(['id', 'name', 'student_id'])
            : collect();

        $query = DB::table('fees')
            ->join('students', 'fees.student_id', '=', 'students.id')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->select([
                'fees.id',
                'fees.type',
                'fees.amount',
                'fees.month',
                'fees.year',
                'fees.label',
                'fees.due_date',
                'fees.status',
                'students.name as student_name',
                'students.student_id as student_code',
                'students.photo as student_photo',
                'classes.name as class_name',
            ])
            ->when($this->search, fn ($q) =>
                $q->where(fn($sub) =>
                    $sub->where('students.name', 'like', '%' . $this->search . '%')
                        ->orWhere('students.student_id', 'like', '%' . $this->search . '%')
                        ->orWhere('fees.label', 'like', '%' . $this->search . '%')
                )
            )
            ->when($this->filterClassId,   fn ($q) => $q->where('students.class_id', $this->filterClassId))
            ->when($this->filterStudentId, fn ($q) => $q->where('fees.student_id', $this->filterStudentId))
            ->when($this->filterStatus !== '', fn ($q) => $q->where('fees.status', $this->filterStatus))
            ->when($this->filterMonth,     fn ($q) => $q->where('fees.month', $this->filterMonth))
            ->when($this->filterYear,      fn ($q) => $q->where('fees.year', $this->filterYear))
            ->orderByDesc('fees.created_at')
            ->orderByDesc('fees.id');

        $summary = clone $query;
        $totalAmount = $summary->sum('fees.amount');
        
        $fees = $query->paginate(20);
        $years = range(now()->year - 2, now()->year + 1);

        return view('livewire.pages.backend.admin-fee-index',
            compact('fees', 'allClasses', 'allStudents', 'years', 'totalAmount')
        )->layout('layouts.app');
    }
}
