<?php

namespace App\Livewire\Backend;

use App\Models\Student;
use Livewire\Component;

class AdminStudentShow extends Component
{
    public int $studentId;

    public function mount(Student $student): void
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'accountant']), 403);
        $this->studentId = $student->id;
    }

    public function render()
    {
        $student = Student::with([
            'class',
            'section',
            'user',
            'fees',
            'attendance' => fn ($q) => $q->orderBy('date', 'desc')->limit(30),
            'results.exam.examGroup',
            'results.exam.subject',
        ])->findOrFail($this->studentId);

        return view('livewire.pages.backend.admin-student-show', compact('student'))
            ->layout('layouts.app');
    }
}
