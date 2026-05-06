<?php

namespace App\Livewire\Backend;

use App\Models\ClassModel;
use App\Models\Section;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminAttendanceMarkSheet extends Component
{
    // ── Selection ────────────────────────────────────────────
    public string $class_id   = '';
    public string $section_id = '';
    public string $date       = '';

    // ── Sheet state ──────────────────────────────────────────
    public bool  $sheetLoaded = false;
    public array $rows        = [];

    // ── Mount ────────────────────────────────────────────────
    public function mount(): void
    {
        $this->date = now()->format('Y-m-d');
    }

    // ── Clear sheet when selection changes ───────────────────
    public function updatedClassId(): void
    {
        $this->section_id  = '';
        $this->rows        = [];
        $this->sheetLoaded = false;
    }

    public function updatedSectionId(): void
    {
        $this->rows        = [];
        $this->sheetLoaded = false;
    }

    public function updatedDate(): void
    {
        $this->rows        = [];
        $this->sheetLoaded = false;
    }

    // ── Render ───────────────────────────────────────────────
    public function render()
    {
        $allClasses  = ClassModel::orderBy('name')->get();
        $allSections = $this->class_id
            ? Section::where('class_id', $this->class_id)->orderBy('name')->get()
            : collect();

        return view('livewire.pages.backend.admin-attendance-mark-sheet',
            compact('allClasses', 'allSections')
        )->layout('layouts.app');
    }

    // ── Load students + pre-fill existing attendance ─────────
    public function loadSheet(): void
    {
        $this->validate([
            'class_id' => 'required|exists:classes,id',
            'date'     => 'required|date|before_or_equal:today',
        ]);

        $students = Student::with('section')
            ->where('class_id', $this->class_id)
            ->when($this->section_id, fn ($q) => $q->where('section_id', $this->section_id))
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        if ($students->isEmpty()) {
            session()->flash('error', 'No active students found for the selected class/section.');
            return;
        }

        // Pre-load any existing attendance for this date
        $existing = DB::table('attendance')
            ->where('date', $this->date)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        $this->rows = $students->map(fn ($s) => [
            'student_id'   => $s->id,
            'name'         => $s->name,
            'photo'        => $s->photo,
            'student_code' => $s->student_id,
            'section'      => $s->section?->name ?? '—',
            'status'       => $existing[$s->id]->status ?? 'present',
            'note'         => $existing[$s->id]->note   ?? '',
        ])->toArray();

        $this->sheetLoaded = true;
    }

    // ── Bulk-set all rows to one status ──────────────────────
    public function markAll(string $status): void
    {
        if (! in_array($status, ['present', 'absent', 'late', 'excused'])) {
            return;
        }

        $this->rows = collect($this->rows)->map(function ($row) use ($status) {
            $row['status'] = $status;
            return $row;
        })->toArray();
    }

    // ── Save via upsert on UNIQUE KEY (student_id, date) ─────
    public function save(): void
    {
        abort_unless(
            auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('teacher'),
            403
        );

        if (empty($this->rows)) {
            session()->flash('error', 'No students loaded. Please load the sheet first.');
            return;
        }

        $valid = ['present', 'absent', 'late', 'excused'];

        $data = collect($this->rows)->map(fn ($row) => [
            'student_id' => (int) $row['student_id'],
            'class_id'   => (int) $this->class_id,
            'date'       => $this->date,
            'status'     => in_array($row['status'], $valid) ? $row['status'] : 'present',
            'note'       => !empty($row['note']) ? trim($row['note']) : null,
            'marked_by'  => Auth::id(),
        ])->toArray();

        // Uses MySQL INSERT ... ON DUPLICATE KEY UPDATE
        DB::table('attendance')->upsert(
            $data,
            ['student_id', 'date'],                        // unique constraint columns
            ['class_id', 'status', 'note', 'marked_by']   // columns to update on conflict
        );

        session()->flash('success',
            count($data) . ' attendance record(s) saved for ' .
            Carbon::parse($this->date)->format('d M Y') . '.'
        );
    }
}
