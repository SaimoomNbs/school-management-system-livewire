<?php

namespace App\Livewire\Backend;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StudentMyAttendance extends Component
{
    public int    $studentId;
    public string $filterMonth = '';
    public string $filterYear  = '';

    // ── Mount — must be a student ────────────────────────────
    public function mount(): void
    {
        $student = auth()->user()?->student;
        abort_unless($student, 403);

        $this->studentId   = $student->id;
        $this->filterMonth = now()->format('m');
        $this->filterYear  = now()->format('Y');
    }

    // ── Month navigation helpers ──────────────────────────────
    public function prevMonth(): void
    {
        $dt = Carbon::create((int) $this->filterYear, (int) $this->filterMonth, 1)->subMonth();
        $this->filterMonth = $dt->format('m');
        $this->filterYear  = $dt->format('Y');
    }

    public function nextMonth(): void
    {
        $dt = Carbon::create((int) $this->filterYear, (int) $this->filterMonth, 1)->addMonth();
        // Do not go beyond current month
        if ($dt->lte(now()->startOfMonth())) {
            $this->filterMonth = $dt->format('m');
            $this->filterYear  = $dt->format('Y');
        }
    }

    // ── Render ───────────────────────────────────────────────
    public function render()
    {
        $records = DB::table('attendance')
            ->where('student_id', $this->studentId)
            ->when($this->filterMonth && $this->filterYear, fn ($q) =>
                $q->whereYear('date', $this->filterYear)->whereMonth('date', $this->filterMonth)
            )
            ->orderBy('date')
            ->get();

        $summary = [
            'present' => $records->where('status', 'present')->count(),
            'absent'  => $records->where('status', 'absent')->count(),
            'late'    => $records->where('status', 'late')->count(),
            'excused' => $records->where('status', 'excused')->count(),
        ];

        // Key records by date string for O(1) calendar lookup
        $byDay = $records->keyBy(fn ($r) => substr($r->date, 0, 10));

        $calDate = Carbon::create((int) $this->filterYear, (int) $this->filterMonth, 1);

        $daysInMonth    = $calDate->daysInMonth;
        $firstDayOfWeek = $calDate->dayOfWeekIso; // 1=Mon … 7=Sun

        $years = range(now()->year - 3, now()->year);

        return view('livewire.pages.backend.student-my-attendance',
            compact('records', 'summary', 'byDay', 'daysInMonth', 'firstDayOfWeek', 'years')
        )->layout('layouts.app');
    }
}
