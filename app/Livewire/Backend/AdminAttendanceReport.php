<?php

namespace App\Livewire\Backend;

use App\Models\ClassModel;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AdminAttendanceReport extends Component
{
    use WithPagination;

    // ── Filters ──────────────────────────────────────────────
    public string $filterClassId   = '';
    public string $filterSectionId = '';
    public string $filterStudentId = '';
    public string $filterMonth     = '';
    public string $filterYear      = '';

    // ── Lifecycle ────────────────────────────────────────────
    public function mount(): void
    {
        $this->filterMonth = now()->format('m');
        $this->filterYear  = now()->format('Y');
    }

    public function updatingFilterClassId(): void
    {
        $this->filterSectionId = '';
        $this->filterStudentId = '';
        $this->resetPage();
    }

    public function updatingFilterSectionId(): void { $this->resetPage(); }
    public function updatingFilterStudentId(): void { $this->resetPage(); }
    public function updatingFilterMonth(): void     { $this->resetPage(); }
    public function updatingFilterYear(): void      { $this->resetPage(); }

    // ── Render ───────────────────────────────────────────────
    public function render()
    {
        $allClasses  = ClassModel::orderBy('name')->get();
        $allSections = $this->filterClassId
            ? Section::where('class_id', $this->filterClassId)->orderBy('name')->get()
            : collect();
        $allStudents = $this->filterClassId
            ? Student::where('class_id', $this->filterClassId)->orderBy('name')->get(['id', 'name', 'student_id'])
            : collect();

        // Resolve section → student IDs once
        $sectionStudentIds = $this->filterSectionId
            ? Student::where('section_id', $this->filterSectionId)->pluck('id')
            : null;

        // Factory closure — fresh DB query builder each call (no paginate interference)
        $base = function () use ($sectionStudentIds) {
            return DB::table('attendance')
                ->when($this->filterClassId,   fn ($q) => $q->where('class_id',   $this->filterClassId))
                ->when($sectionStudentIds,      fn ($q) => $q->whereIn('student_id', $sectionStudentIds))
                ->when($this->filterStudentId, fn ($q) => $q->where('student_id', $this->filterStudentId))
                ->when($this->filterMonth && $this->filterYear, fn ($q) =>
                    $q->whereYear('date', $this->filterYear)->whereMonth('date', $this->filterMonth)
                )
                ->when($this->filterYear && ! $this->filterMonth, fn ($q) =>
                    $q->whereYear('date', $this->filterYear)
                );
        };

        // Summary counts (5 separate cheap COUNT queries)
        $summary = [
            'total'   => $base()->count(),
            'present' => $base()->where('status', 'present')->count(),
            'absent'  => $base()->where('status', 'absent')->count(),
            'late'    => $base()->where('status', 'late')->count(),
            'excused' => $base()->where('status', 'excused')->count(),
        ];

        // Paginated records with JOINed student, class, section, marker
        $records = $base()
            ->join('students', 'attendance.student_id', '=', 'students.id')
            ->join('classes',  'attendance.class_id',   '=', 'classes.id')
            ->leftJoin('sections', 'students.section_id', '=', 'sections.id')
            ->leftJoin('users as markers', 'attendance.marked_by', '=', 'markers.id')
            ->select([
                'attendance.id',
                'attendance.date',
                'attendance.status',
                'attendance.note',
                'students.name      as student_name',
                'students.student_id as student_code',
                'students.photo     as student_photo',
                'classes.name       as class_name',
                'sections.name      as section_name',
                'markers.name       as marker_name',
            ])
            ->orderByDesc('attendance.date')
            ->orderBy('students.name')
            ->paginate(20);

        $years = range(now()->year - 3, now()->year + 1);

        return view('livewire.pages.backend.admin-attendance-report',
            compact('records', 'summary', 'allClasses', 'allSections', 'allStudents', 'years')
        )->layout('layouts.app');
    }
}
